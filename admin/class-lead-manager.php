<?php
/**
 * Lead Management System
 * Capture, track, and manage student leads
 *
 * @package CK_OneForm
 */

if (!defined('ABSPATH')) {
    exit;
}

class CK_OneForm_Lead_Manager {

    /**
     * Initialize
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_menu'));
        add_action('wp_ajax_ck_capture_lead', array(__CLASS__, 'ajax_capture_lead'));
        add_action('wp_ajax_nopriv_ck_capture_lead', array(__CLASS__, 'ajax_capture_lead'));
        add_action('admin_post_ck_update_lead_status', array(__CLASS__, 'update_lead_status'));
        add_action('admin_post_ck_delete_lead', array(__CLASS__, 'delete_lead'));
        add_action('admin_post_ck_add_lead_note', array(__CLASS__, 'add_lead_note'));
        add_action('admin_post_ck_export_leads', array(__CLASS__, 'export_leads'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
    }

    /**
     * Enqueue scripts
     */
    public static function enqueue_scripts($hook) {
        if (strpos($hook, 'ck-lead-management') !== false) {
            wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.9.1', true);
        }
    }

    /**
     * Add menu
     */
    public static function add_menu() {
        add_submenu_page(
            'ck-oneform-main',
            'Lead Management',
            'Lead Management',
            'manage_options',
            'ck-lead-management',
            array(__CLASS__, 'leads_page')
        );
    }

    /**
     * Create leads table
     */
    public static function create_leads_table() {
        global $wpdb;

        $table = $wpdb->prefix . 'ck_oneform_leads';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            phone varchar(20) NOT NULL,
            source varchar(100) DEFAULT NULL,
            interest varchar(255) DEFAULT NULL,
            city varchar(100) DEFAULT NULL,
            state varchar(100) DEFAULT NULL,
            message text,
            status varchar(20) DEFAULT 'new',
            priority varchar(20) DEFAULT 'normal',
            assigned_to bigint(20) DEFAULT NULL,
            notes text,
            utm_source varchar(100) DEFAULT NULL,
            utm_medium varchar(100) DEFAULT NULL,
            utm_campaign varchar(100) DEFAULT NULL,
            ip_address varchar(50) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            last_contacted datetime DEFAULT NULL,
            converted_at datetime DEFAULT NULL,
            PRIMARY KEY (id),
            KEY email (email),
            KEY status (status),
            KEY created_at (created_at)
        ) $charset_collate;";

        $wpdb->query($sql);
    }

    /**
     * Main leads page
     */
    public static function leads_page() {
        global $wpdb;

        // Create table if not exists
        self::create_leads_table();

        $table = $wpdb->prefix . 'ck_oneform_leads';

        // Check if table exists
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table'");

        // Get filter parameters
        $status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
        $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
        $date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : '';
        $date_to = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : '';

        // Build query
        $where = array('1=1');
        if ($status_filter) {
            $where[] = $wpdb->prepare("status = %s", $status_filter);
        }
        if ($search) {
            $where[] = $wpdb->prepare("(name LIKE %s OR email LIKE %s OR phone LIKE %s)", "%$search%", "%$search%", "%$search%");
        }
        if ($date_from) {
            $where[] = $wpdb->prepare("DATE(created_at) >= %s", $date_from);
        }
        if ($date_to) {
            $where[] = $wpdb->prepare("DATE(created_at) <= %s", $date_to);
        }

        $where_clause = implode(' AND ', $where);

        // Get leads
        $leads = array();
        $stats = array(
            'total' => 0,
            'new' => 0,
            'contacted' => 0,
            'qualified' => 0,
            'converted' => 0,
            'lost' => 0
        );

        if ($table_exists) {
            $leads = $wpdb->get_results("SELECT * FROM $table WHERE $where_clause ORDER BY created_at DESC LIMIT 100");

            // Get statistics
            $stats['total'] = $wpdb->get_var("SELECT COUNT(*) FROM $table");
            $stats['new'] = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'new'");
            $stats['contacted'] = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'contacted'");
            $stats['qualified'] = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'qualified'");
            $stats['converted'] = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'converted'");
            $stats['lost'] = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'lost'");

            // Get leads by source for chart
            $leads_by_source = $wpdb->get_results("SELECT source, COUNT(*) as count FROM $table GROUP BY source ORDER BY count DESC LIMIT 10");

            // Get leads trend (last 30 days)
            $leads_trend = $wpdb->get_results("
                SELECT DATE(created_at) as date, COUNT(*) as count
                FROM $table
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(created_at)
                ORDER BY date ASC
            ");
        }

        ?>
        <div class="wrap ck-lead-management">
            <h1>
                <span class="dashicons dashicons-groups" style="font-size: 30px; margin-right: 10px;"></span>
                Lead Management
            </h1>

            <?php if (isset($_GET['lead_updated'])): ?>
            <div class="notice notice-success"><p>Lead updated successfully!</p></div>
            <?php endif; ?>

            <?php if (isset($_GET['lead_deleted'])): ?>
            <div class="notice notice-success"><p>Lead deleted successfully!</p></div>
            <?php endif; ?>

            <?php if (isset($_GET['note_added'])): ?>
            <div class="notice notice-success"><p>Note added successfully!</p></div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="lead-stats">
                <div class="stat-card total">
                    <div class="stat-icon"><span class="dashicons dashicons-chart-bar"></span></div>
                    <div class="stat-info">
                        <h3><?php echo number_format($stats['total']); ?></h3>
                        <p>Total Leads</p>
                    </div>
                </div>
                <div class="stat-card new">
                    <div class="stat-icon"><span class="dashicons dashicons-star-filled"></span></div>
                    <div class="stat-info">
                        <h3><?php echo number_format($stats['new']); ?></h3>
                        <p>New Leads</p>
                    </div>
                </div>
                <div class="stat-card contacted">
                    <div class="stat-icon"><span class="dashicons dashicons-phone"></span></div>
                    <div class="stat-info">
                        <h3><?php echo number_format($stats['contacted']); ?></h3>
                        <p>Contacted</p>
                    </div>
                </div>
                <div class="stat-card qualified">
                    <div class="stat-icon"><span class="dashicons dashicons-yes-alt"></span></div>
                    <div class="stat-info">
                        <h3><?php echo number_format($stats['qualified']); ?></h3>
                        <p>Qualified</p>
                    </div>
                </div>
                <div class="stat-card converted">
                    <div class="stat-icon"><span class="dashicons dashicons-awards"></span></div>
                    <div class="stat-info">
                        <h3><?php echo number_format($stats['converted']); ?></h3>
                        <p>Converted</p>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="charts-row">
                <div class="chart-box">
                    <h3>Lead Sources</h3>
                    <canvas id="sourceChart" width="400" height="300"></canvas>
                </div>
                <div class="chart-box">
                    <h3>Leads Trend (Last 30 Days)</h3>
                    <canvas id="trendChart" width="400" height="300"></canvas>
                </div>
            </div>

            <!-- Actions Bar -->
            <div class="actions-bar">
                <div class="filters">
                    <form method="get" action="">
                        <input type="hidden" name="page" value="ck-lead-management">
                        <input type="text" name="search" placeholder="Search name, email, phone..." value="<?php echo esc_attr($search); ?>" class="search-input">
                        <select name="status">
                            <option value="">All Status</option>
                            <option value="new" <?php selected($status_filter, 'new'); ?>>New</option>
                            <option value="contacted" <?php selected($status_filter, 'contacted'); ?>>Contacted</option>
                            <option value="qualified" <?php selected($status_filter, 'qualified'); ?>>Qualified</option>
                            <option value="converted" <?php selected($status_filter, 'converted'); ?>>Converted</option>
                            <option value="lost" <?php selected($status_filter, 'lost'); ?>>Lost</option>
                        </select>
                        <input type="date" name="date_from" value="<?php echo esc_attr($date_from); ?>" placeholder="From Date">
                        <input type="date" name="date_to" value="<?php echo esc_attr($date_to); ?>" placeholder="To Date">
                        <button type="submit" class="button">Filter</button>
                        <a href="<?php echo admin_url('admin.php?page=ck-lead-management'); ?>" class="button">Clear</a>
                    </form>
                </div>
                <div class="export-actions">
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" style="display: inline;">
                        <input type="hidden" name="action" value="ck_export_leads">
                        <?php wp_nonce_field('ck_export_leads_nonce', 'export_nonce'); ?>
                        <button type="submit" class="button button-secondary">
                            <span class="dashicons dashicons-download" style="vertical-align: middle;"></span> Export CSV
                        </button>
                    </form>
                </div>
            </div>

            <!-- Leads Table -->
            <div class="leads-table-container">
                <?php if (!$table_exists): ?>
                <div class="notice notice-error">
                    <p>Leads table not found. <a href="<?php echo admin_url('admin-post.php?action=ck_fix_database_tables'); ?>" class="button">Fix Database</a></p>
                </div>
                <?php elseif (empty($leads)): ?>
                <div class="empty-state">
                    <span class="dashicons dashicons-groups"></span>
                    <h3>No Leads Found</h3>
                    <p>Leads will appear here when captured from forms.</p>
                    <p><strong>Shortcode:</strong> <code>[ck_lead_capture_form]</code></p>
                </div>
                <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Interest</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leads as $lead): ?>
                        <tr>
                            <td><strong>#<?php echo $lead->id; ?></strong></td>
                            <td>
                                <strong><?php echo esc_html($lead->name); ?></strong>
                                <?php if ($lead->city || $lead->state): ?>
                                <br><small><?php echo esc_html(($lead->city ? $lead->city : '') . ($lead->city && $lead->state ? ', ' : '') . ($lead->state ? $lead->state : '')); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="mailto:<?php echo esc_attr($lead->email); ?>"><?php echo esc_html($lead->email); ?></a><br>
                                <a href="tel:<?php echo esc_attr($lead->phone); ?>"><?php echo esc_html($lead->phone); ?></a>
                            </td>
                            <td><?php echo esc_html($lead->interest ?: 'Not specified'); ?></td>
                            <td>
                                <span class="source-badge"><?php echo esc_html($lead->source ?: 'Direct'); ?></span>
                                <?php if ($lead->utm_campaign): ?>
                                <br><small>Campaign: <?php echo esc_html($lead->utm_campaign); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" style="display: inline;">
                                    <input type="hidden" name="action" value="ck_update_lead_status">
                                    <input type="hidden" name="lead_id" value="<?php echo $lead->id; ?>">
                                    <?php wp_nonce_field('ck_update_lead_nonce', 'lead_nonce'); ?>
                                    <select name="status" onchange="this.form.submit()" class="status-select status-<?php echo esc_attr($lead->status); ?>">
                                        <option value="new" <?php selected($lead->status, 'new'); ?>>New</option>
                                        <option value="contacted" <?php selected($lead->status, 'contacted'); ?>>Contacted</option>
                                        <option value="qualified" <?php selected($lead->status, 'qualified'); ?>>Qualified</option>
                                        <option value="converted" <?php selected($lead->status, 'converted'); ?>>Converted</option>
                                        <option value="lost" <?php selected($lead->status, 'lost'); ?>>Lost</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" style="display: inline;">
                                    <input type="hidden" name="action" value="ck_update_lead_status">
                                    <input type="hidden" name="lead_id" value="<?php echo $lead->id; ?>">
                                    <?php wp_nonce_field('ck_update_lead_nonce', 'lead_nonce'); ?>
                                    <input type="hidden" name="status" value="<?php echo esc_attr($lead->status); ?>">
                                    <select name="priority" onchange="this.form.submit()" class="priority-select priority-<?php echo esc_attr($lead->priority); ?>">
                                        <option value="low" <?php selected($lead->priority, 'low'); ?>>Low</option>
                                        <option value="normal" <?php selected($lead->priority, 'normal'); ?>>Normal</option>
                                        <option value="high" <?php selected($lead->priority, 'high'); ?>>High</option>
                                        <option value="urgent" <?php selected($lead->priority, 'urgent'); ?>>Urgent</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <?php echo date('M d, Y', strtotime($lead->created_at)); ?><br>
                                <small><?php echo date('h:i A', strtotime($lead->created_at)); ?></small>
                            </td>
                            <td>
                                <button class="button button-small view-lead-btn" data-lead-id="<?php echo $lead->id; ?>" data-lead='<?php echo esc_attr(json_encode($lead)); ?>'>
                                    <span class="dashicons dashicons-visibility" style="vertical-align: middle;"></span>
                                </button>
                                <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=ck_delete_lead&lead_id=' . $lead->id), 'ck_delete_lead_nonce', 'delete_nonce'); ?>"
                                   class="button button-small"
                                   onclick="return confirm('Are you sure you want to delete this lead?');">
                                    <span class="dashicons dashicons-trash" style="vertical-align: middle;"></span>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>

            <!-- Lead Detail Modal -->
            <div id="lead-modal" class="lead-modal" style="display: none;">
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <h2>Lead Details</h2>
                    <div id="lead-details"></div>

                    <h3>Add Note</h3>
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <input type="hidden" name="action" value="ck_add_lead_note">
                        <input type="hidden" name="lead_id" id="note-lead-id" value="">
                        <?php wp_nonce_field('ck_add_note_nonce', 'note_nonce'); ?>
                        <textarea name="note" rows="4" placeholder="Add your notes here..." style="width: 100%;"></textarea>
                        <button type="submit" class="button button-primary" style="margin-top: 10px;">Add Note</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            // View lead modal
            $('.view-lead-btn').on('click', function() {
                var lead = $(this).data('lead');
                var html = `
                    <table class="form-table">
                        <tr><th>Name:</th><td><strong>${lead.name}</strong></td></tr>
                        <tr><th>Email:</th><td><a href="mailto:${lead.email}">${lead.email}</a></td></tr>
                        <tr><th>Phone:</th><td><a href="tel:${lead.phone}">${lead.phone}</a></td></tr>
                        <tr><th>Interest:</th><td>${lead.interest || 'Not specified'}</td></tr>
                        <tr><th>Location:</th><td>${lead.city || ''} ${lead.city && lead.state ? ',' : ''} ${lead.state || ''}</td></tr>
                        <tr><th>Source:</th><td>${lead.source || 'Direct'}</td></tr>
                        <tr><th>Message:</th><td>${lead.message || 'No message'}</td></tr>
                        <tr><th>IP Address:</th><td>${lead.ip_address || 'N/A'}</td></tr>
                        <tr><th>Created:</th><td>${lead.created_at}</td></tr>
                        <tr><th>Notes:</th><td><pre style="white-space: pre-wrap;">${lead.notes || 'No notes yet'}</pre></td></tr>
                    </table>
                `;
                $('#lead-details').html(html);
                $('#note-lead-id').val(lead.id);
                $('#lead-modal').fadeIn();
            });

            $('.close-modal').on('click', function() {
                $('#lead-modal').fadeOut();
            });

            // Charts
            <?php if (!empty($leads_by_source)): ?>
            var sourceCtx = document.getElementById('sourceChart').getContext('2d');
            new Chart(sourceCtx, {
                type: 'doughnut',
                data: {
                    labels: [<?php echo implode(',', array_map(function($s) { return "'" . ($s->source ?: 'Direct') . "'"; }, $leads_by_source)); ?>],
                    datasets: [{
                        data: [<?php echo implode(',', array_map(function($s) { return $s->count; }, $leads_by_source)); ?>],
                        backgroundColor: ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe', '#43e97b', '#38f9d7', '#fbbf24', '#f59e0b']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
            <?php endif; ?>

            <?php if (!empty($leads_trend)): ?>
            var trendCtx = document.getElementById('trendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: [<?php echo implode(',', array_map(function($t) { return "'" . date('M d', strtotime($t->date)) . "'"; }, $leads_trend)); ?>],
                    datasets: [{
                        label: 'Leads',
                        data: [<?php echo implode(',', array_map(function($t) { return $t->count; }, $leads_trend)); ?>],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            <?php endif; ?>
        });
        </script>

        <style>
        .ck-lead-management {
            max-width: 1400px;
        }

        .lead-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon .dashicons {
            font-size: 30px;
            width: 30px;
            height: 30px;
            color: white;
        }

        .stat-card.total .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-card.new .stat-icon { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); }
        .stat-card.contacted .stat-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stat-card.qualified .stat-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-card.converted .stat-icon { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }

        .stat-info h3 {
            margin: 0;
            font-size: 2rem;
            color: #333;
        }

        .stat-info p {
            margin: 5px 0 0;
            color: #666;
        }

        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-box {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .chart-box h3 {
            margin: 0 0 15px;
            color: #333;
        }

        .actions-bar {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .filters form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            width: 250px;
        }

        .filters select, .filters input[type="date"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .leads-table-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state .dashicons {
            font-size: 60px;
            width: 60px;
            height: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .source-badge {
            background: #e0e7ff;
            color: #3730a3;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-select, .priority-select {
            padding: 6px 10px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
        }

        .status-new { background: #fef3c7; color: #92400e; }
        .status-contacted { background: #dbeafe; color: #1e40af; }
        .status-qualified { background: #fce7f3; color: #9d174d; }
        .status-converted { background: #d1fae5; color: #065f46; }
        .status-lost { background: #fee2e2; color: #991b1b; }

        .priority-low { background: #f3f4f6; color: #4b5563; }
        .priority-normal { background: #dbeafe; color: #1e40af; }
        .priority-high { background: #fef3c7; color: #92400e; }
        .priority-urgent { background: #fee2e2; color: #991b1b; }

        .lead-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 100000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 30px;
            width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 30px;
            cursor: pointer;
            color: #666;
        }

        .close-modal:hover {
            color: #333;
        }

        @media (max-width: 768px) {
            .charts-row {
                grid-template-columns: 1fr;
            }

            .actions-bar {
                flex-direction: column;
                gap: 15px;
            }

            .filters form {
                flex-direction: column;
            }

            .search-input {
                width: 100%;
            }
        }
        </style>
        <?php
    }

    /**
     * AJAX capture lead
     */
    public static function ajax_capture_lead() {
        check_ajax_referer('ck-capture-lead', 'nonce');

        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_leads';

        // Create table if not exists
        self::create_leads_table();

        $data = array(
            'name' => sanitize_text_field($_POST['name'] ?? ''),
            'email' => sanitize_email($_POST['email'] ?? ''),
            'phone' => sanitize_text_field($_POST['phone'] ?? ''),
            'source' => sanitize_text_field($_POST['source'] ?? 'Website'),
            'interest' => sanitize_text_field($_POST['interest'] ?? ''),
            'city' => sanitize_text_field($_POST['city'] ?? ''),
            'state' => sanitize_text_field($_POST['state'] ?? ''),
            'message' => sanitize_textarea_field($_POST['message'] ?? ''),
            'utm_source' => sanitize_text_field($_POST['utm_source'] ?? ''),
            'utm_medium' => sanitize_text_field($_POST['utm_medium'] ?? ''),
            'utm_campaign' => sanitize_text_field($_POST['utm_campaign'] ?? ''),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
        );

        // Validate required fields
        if (empty($data['name']) || empty($data['email']) || empty($data['phone'])) {
            wp_send_json_error(array('message' => 'Name, email, and phone are required'));
        }

        if (!is_email($data['email'])) {
            wp_send_json_error(array('message' => 'Invalid email address'));
        }

        $inserted = $wpdb->insert($table, $data);

        if ($inserted) {
            // Send notification email to admin
            self::send_lead_notification($data);

            wp_send_json_success(array('message' => 'Thank you! We will contact you soon.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to save your information. Please try again.'));
        }
    }

    /**
     * Send lead notification
     */
    private static function send_lead_notification($lead) {
        $admin_email = get_option('admin_email');
        $subject = 'New Lead Captured: ' . $lead['name'];
        $message = "A new lead has been captured:\n\n";
        $message .= "Name: {$lead['name']}\n";
        $message .= "Email: {$lead['email']}\n";
        $message .= "Phone: {$lead['phone']}\n";
        $message .= "Interest: {$lead['interest']}\n";
        $message .= "Location: {$lead['city']}, {$lead['state']}\n";
        $message .= "Source: {$lead['source']}\n";
        $message .= "Message: {$lead['message']}\n\n";
        $message .= "View all leads: " . admin_url('admin.php?page=ck-lead-management');

        wp_mail($admin_email, $subject, $message);
    }

    /**
     * Update lead status
     */
    public static function update_lead_status() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('ck_update_lead_nonce', 'lead_nonce');

        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_leads';

        $lead_id = intval($_POST['lead_id']);
        $update_data = array();

        if (isset($_POST['status'])) {
            $update_data['status'] = sanitize_text_field($_POST['status']);
            if ($_POST['status'] === 'contacted') {
                $update_data['last_contacted'] = current_time('mysql');
            }
            if ($_POST['status'] === 'converted') {
                $update_data['converted_at'] = current_time('mysql');
            }
        }

        if (isset($_POST['priority'])) {
            $update_data['priority'] = sanitize_text_field($_POST['priority']);
        }

        if (!empty($update_data)) {
            $wpdb->update($table, $update_data, array('id' => $lead_id));
        }

        wp_redirect(admin_url('admin.php?page=ck-lead-management&lead_updated=1'));
        exit;
    }

    /**
     * Delete lead
     */
    public static function delete_lead() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('ck_delete_lead_nonce', 'delete_nonce');

        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_leads';

        $lead_id = intval($_GET['lead_id']);
        $wpdb->delete($table, array('id' => $lead_id));

        wp_redirect(admin_url('admin.php?page=ck-lead-management&lead_deleted=1'));
        exit;
    }

    /**
     * Add note to lead
     */
    public static function add_lead_note() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('ck_add_note_nonce', 'note_nonce');

        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_leads';

        $lead_id = intval($_POST['lead_id']);
        $note = sanitize_textarea_field($_POST['note']);

        $lead = $wpdb->get_row($wpdb->prepare("SELECT notes FROM $table WHERE id = %d", $lead_id));
        $current_notes = $lead->notes ?? '';

        $new_note = date('Y-m-d H:i:s') . " - " . wp_get_current_user()->display_name . ":\n" . $note . "\n\n";
        $updated_notes = $new_note . $current_notes;

        $wpdb->update($table, array('notes' => $updated_notes), array('id' => $lead_id));

        wp_redirect(admin_url('admin.php?page=ck-lead-management&note_added=1'));
        exit;
    }

    /**
     * Export leads to CSV
     */
    public static function export_leads() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('ck_export_leads_nonce', 'export_nonce');

        global $wpdb;
        $table = $wpdb->prefix . 'ck_oneform_leads';

        $leads = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC", ARRAY_A);

        if (empty($leads)) {
            wp_die('No leads to export');
        }

        $filename = 'leads_export_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // CSV headers
        fputcsv($output, array_keys($leads[0]));

        // CSV data
        foreach ($leads as $lead) {
            fputcsv($output, $lead);
        }

        fclose($output);
        exit;
    }
}

// Initialize
CK_OneForm_Lead_Manager::init();
