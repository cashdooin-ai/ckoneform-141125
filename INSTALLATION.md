# CollegeKampus OneForm - Installation Guide

## Quick Start Guide

This guide will help you install and configure the CollegeKampus OneForm plugin on your WordPress site.

## Prerequisites

Before installation, ensure your hosting environment meets these requirements:

- WordPress 5.0 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- At least 64MB PHP memory limit
- File upload capability enabled

## Installation Steps

### Step 1: Upload Plugin Files

#### Option A: Via FTP/SFTP

1. Connect to your server using an FTP client (FileZilla, Cyberduck, etc.)
2. Navigate to `/wp-content/plugins/`
3. Upload the entire `collegekampus-oneform` folder
4. Ensure all files and folders are uploaded completely

#### Option B: Via cPanel File Manager

1. Log into your Hostinger cPanel
2. Go to **File Manager**
3. Navigate to `public_html/wp-content/plugins/`
4. Click **Upload** and select the plugin ZIP file
5. Extract the ZIP file

#### Option C: Via WordPress Admin

1. Zip the plugin folder: `collegekampus-oneform.zip`
2. Go to **WordPress Admin → Plugins → Add New**
3. Click **Upload Plugin**
4. Choose the ZIP file
5. Click **Install Now**

### Step 2: Activate the Plugin

1. Go to **WordPress Admin → Plugins**
2. Find "CollegeKampus OneForm" in the list
3. Click **Activate**

The plugin will automatically:
- Create 4 custom database tables
- Create 6 default pages
- Set default options
- Register custom post types

### Step 3: Verify Installation

Check that these pages were created:
1. Go to **Pages → All Pages**
2. You should see:
   - OneForm Home
   - Application Form
   - Student Registration
   - My Applications
   - Application Status
   - Payment

### Step 4: Configure Permalinks

1. Go to **Settings → Permalinks**
2. Select "Post name" or any option except "Plain"
3. Click **Save Changes**

This ensures clean URLs for the plugin pages.

### Step 5: Configure Plugin Settings

1. Go to **OneForm → Settings**

2. **Email Settings:**
   - ✅ Check "Enable Email Notifications"
   - Enter admin email address for notifications
   - Click **Save Settings**

3. **Payment Settings (Optional):**
   - ✅ Check "Enable Payment System" (if needed)
   - Select currency (INR for India)
   - Choose payment gateway (Razorpay recommended for India)
   - Enter API credentials
   - Click **Save Settings**

### Step 6: Add Sample Content

#### Add Courses

1. Go to **OneForm → Courses → Add New**
2. Enter course details:
   ```
   Title: B.Tech Computer Science
   Description: 4-year undergraduate program in Computer Science
   Category: Engineering
   ```
3. Set featured image (optional)
4. Click **Publish**

Repeat for more courses (MBA, BBA, B.Sc, etc.)

#### Add Colleges

1. Go to **OneForm → Colleges → Add New**
2. Enter college details:
   ```
   Title: Delhi University
   Description: Premier university in Delhi
   Type: Government
   ```
3. Set featured image (optional)
4. Click **Publish**

Repeat for more colleges

### Step 7: Set Up Navigation Menu

1. Go to **Appearance → Menus**
2. Create a new menu or edit existing
3. Add these pages:
   - OneForm Home
   - Application Form
   - Application Status
   - My Applications (Dashboard)
4. Arrange order and save menu

### Step 8: Test the System

#### Test Registration

1. Open your site in incognito/private window
2. Go to `/student-registration/`
3. Fill out the registration form
4. Submit and verify:
   - User account is created
   - Welcome email is received
   - Auto-login works

#### Test Application Submission

1. As a logged-in user, go to `/application-form/`
2. Fill out all required fields
3. Upload test documents
4. Submit application
5. Verify:
   - Application is created
   - Confirmation email received
   - Application appears in dashboard

#### Test Admin Panel

1. Login as admin
2. Go to **OneForm → Dashboard**
3. Verify statistics are displayed
4. Go to **OneForm → Applications**
5. See the test application
6. Try updating its status

## Hostinger-Specific Configuration

### Database Optimization

1. Go to **cPanel → phpMyAdmin**
2. Select your WordPress database
3. Check that these tables exist:
   - `wp_ck_oneform_applications`
   - `wp_ck_oneform_submissions_meta`
   - `wp_ck_oneform_payments`
   - `wp_ck_oneform_documents`

### File Upload Settings

1. Check PHP settings in cPanel
2. Ensure these values:
   ```
   upload_max_filesize = 10M
   post_max_size = 10M
   max_execution_time = 300
   memory_limit = 128M
   ```

### Email Configuration

If emails are not sending:

1. Install **WP Mail SMTP** plugin
2. Configure with:
   - From Email: noreply@yourdomain.com
   - From Name: CollegeKampus OneForm
   - Mailer: Use built-in PHP mail()

Or use SMTP:
   - Host: smtp.hostinger.com
   - Port: 587
   - Encryption: TLS
   - Auth: Yes
   - Username: your-email@yourdomain.com
   - Password: your-email-password

## Integration with Existing Site

### Add OneForm to Homepage

Add this shortcode to your homepage:
```
[ck_oneform_home]
```

### Add to Sidebar Widget

1. Go to **Appearance → Widgets**
2. Add **Text** or **HTML** widget
3. Add shortcode:
   ```
   [ck_oneform_application]
   ```

### Custom Page Template

Create a custom template in your theme:
```php
<?php
/* Template Name: OneForm Application */
get_header();
echo do_shortcode('[ck_oneform_application]');
get_footer();
?>
```

## Post-Installation Checklist

- [ ] Plugin activated successfully
- [ ] All 6 pages created
- [ ] Permalinks flushed (saved)
- [ ] Email settings configured
- [ ] Test email sent successfully
- [ ] Sample courses added (at least 3)
- [ ] Sample colleges added (at least 3)
- [ ] Navigation menu updated
- [ ] Test registration completed
- [ ] Test application submitted
- [ ] Admin dashboard accessible
- [ ] Payment gateway configured (if using)
- [ ] File uploads working
- [ ] Mobile responsive checked

## Common Installation Issues

### Issue: Plugin activation fails

**Solution:**
- Check PHP version (must be 7.4+)
- Increase memory limit
- Check error logs

### Issue: Pages not created

**Solution:**
1. Deactivate plugin
2. Delete any partial pages
3. Reactivate plugin

### Issue: 404 errors on pages

**Solution:**
1. Go to **Settings → Permalinks**
2. Click **Save Changes**

### Issue: Database tables not created

**Solution:**
1. Check database user permissions
2. Manually create tables using SQL from `includes/class-database.php`
3. Contact hosting support

### Issue: CSS/JS not loading

**Solution:**
1. Clear browser cache
2. Clear WordPress cache
3. Disable conflicting plugins
4. Check file permissions (755 for folders, 644 for files)

## Security Hardening

After installation:

1. **Change default admin email** in settings
2. **Set strong passwords** for payment gateway APIs
3. **Limit file upload types** in WordPress settings
4. **Enable SSL** for payment pages (required)
5. **Install security plugin** like Wordfence
6. **Regular backups** using UpdraftPlus

## Performance Optimization

1. **Install caching plugin**: WP Rocket or W3 Total Cache
2. **Optimize images**: Use Smush or ShortPixel
3. **Enable CDN**: Cloudflare (free tier works great)
4. **Minify assets**: Use Autoptimize plugin
5. **Database optimization**: Run WP-Optimize weekly

## Backup Before Going Live

1. **Database Backup:**
   ```bash
   # Via cPanel → phpMyAdmin → Export
   # Or via WP plugin: UpdraftPlus
   ```

2. **Files Backup:**
   ```bash
   # Via FTP: Download /wp-content/plugins/collegekampus-oneform/
   # Via cPanel: Create ZIP of plugin folder
   ```

## Going Live Checklist

- [ ] Test all forms thoroughly
- [ ] Test email delivery
- [ ] Test payment processing (in sandbox mode)
- [ ] Check mobile responsiveness
- [ ] Test with different browsers
- [ ] Set up automated backups
- [ ] Configure caching
- [ ] Enable SSL certificate
- [ ] Update payment gateway to live mode
- [ ] Train admin staff
- [ ] Prepare user documentation
- [ ] Set up analytics tracking

## Getting Help

If you encounter issues during installation:

1. Check the troubleshooting section in README.md
2. Review server error logs
3. Contact Hostinger support for server issues
4. Contact plugin support: support@collegekampus.com

## Next Steps

After successful installation:

1. Read the full **README.md** for features and usage
2. Customize form fields (see documentation)
3. Set up email templates
4. Configure payment gateway in live mode
5. Create user documentation for students
6. Set up monitoring and analytics

---

**Installation complete!** Your OneForm system is ready to accept applications.

Visit your site at: `https://yourdomain.com/oneform-home/`
