# CollegeKampus OneForm WordPress Plugin

A comprehensive WordPress plugin for managing college application forms, student registrations, and admissions through a unified platform.

## Features

### Student Features
- **Single Application System** - Apply to multiple colleges with one form
- **User Dashboard** - Track all applications in one place
- **Application Status Tracking** - Real-time status updates
- **Document Upload** - Upload photos and supporting documents
- **Payment Integration** - Online payment for application fees
- **Email Notifications** - Automatic confirmations and status updates
- **PDF Generation** - Download application forms as PDF

### Admin Features
- **Comprehensive Dashboard** - Overview of all applications
- **Application Management** - View, approve, reject applications
- **User Management** - Manage student registrations
- **Course & College Management** - Add and manage courses and colleges
- **Payment Tracking** - Monitor all transactions
- **Export Functionality** - Export applications to CSV
- **Email System** - Automated email notifications
- **Settings Panel** - Configure plugin options

### Technical Features
- **Custom Post Types** - Forms, Courses, Colleges
- **Custom Database Tables** - Optimized data storage
- **AJAX Functionality** - Smooth user experience
- **Responsive Design** - Mobile-friendly interface
- **Shortcode System** - Easy page integration
- **Security** - Nonce verification, data sanitization
- **Extensible** - Hooks and filters for customization

## Installation

### Method 1: Manual Installation

1. **Upload the Plugin**
   ```bash
   # Copy the entire plugin folder to your WordPress plugins directory
   cp -r collegekampus-oneform /path/to/wordpress/wp-content/plugins/
   ```

2. **Activate the Plugin**
   - Go to WordPress Admin → Plugins
   - Find "CollegeKampus OneForm"
   - Click "Activate"

3. **Plugin will automatically:**
   - Create required database tables
   - Create default pages
   - Set default options

### Method 2: ZIP Upload

1. Zip the plugin folder:
   ```bash
   zip -r collegekampus-oneform.zip collegekampus-oneform/
   ```

2. Go to WordPress Admin → Plugins → Add New → Upload Plugin
3. Choose the ZIP file and click "Install Now"
4. Click "Activate Plugin"

## Initial Setup

### 1. Configure Settings

Navigate to **OneForm → Settings** and configure:

- **Email Settings**
  - Enable/disable email notifications
  - Set admin email address

- **Payment Settings**
  - Enable payment system
  - Choose currency (INR, USD, EUR)
  - Select payment gateway
  - Enter gateway credentials (Razorpay, PayU, Paytm)

### 2. Add Courses

1. Go to **OneForm → Courses → Add New**
2. Enter course details:
   - Course name
   - Description
   - Category
   - Featured image
3. Publish the course

### 3. Add Colleges

1. Go to **OneForm → Colleges → Add New**
2. Enter college details:
   - College name
   - Description
   - Type (Government, Private, etc.)
   - Featured image
3. Publish the college

### 4. Customize Application Form

1. Go to **OneForm → Application Forms → Add New**
2. Create your form
3. Customize fields using filters (see Developer Guide)

## Page Structure

The plugin automatically creates these pages:

1. **OneForm Home** (`/oneform-home/`)
   - Landing page with features and statistics
   - Shortcode: `[ck_oneform_home]`

2. **Student Registration** (`/student-registration/`)
   - New user registration form
   - Shortcode: `[ck_oneform_registration]`

3. **Application Form** (`/application-form/`)
   - Main application submission form
   - Shortcode: `[ck_oneform_application]`

4. **My Applications** (`/my-applications/`)
   - User dashboard with all applications
   - Shortcode: `[ck_oneform_dashboard]`

5. **Application Status** (`/application-status/`)
   - Public status check page
   - Shortcode: `[ck_oneform_status]`

6. **Payment** (`/payment/`)
   - Payment processing page
   - Shortcode: `[ck_oneform_payment]`

## Available Shortcodes

| Shortcode | Description | Usage |
|-----------|-------------|-------|
| `[ck_oneform_home]` | Display home page | `[ck_oneform_home]` |
| `[ck_oneform_registration]` | Registration form | `[ck_oneform_registration]` |
| `[ck_oneform_application]` | Application form | `[ck_oneform_application form_id="1"]` |
| `[ck_oneform_dashboard]` | User dashboard | `[ck_oneform_dashboard]` |
| `[ck_oneform_status]` | Status checker | `[ck_oneform_status]` |
| `[ck_oneform_payment]` | Payment page | `[ck_oneform_payment]` |
| `[ck_oneform_courses]` | Courses list | `[ck_oneform_courses limit="10" category="engineering"]` |
| `[ck_oneform_colleges]` | Colleges list | `[ck_oneform_colleges limit="10" type="government"]` |

## Navigation Menu

To add OneForm pages to your site menu:

1. Go to **Appearance → Menus**
2. Select or create a menu
3. Add the OneForm pages from the "Pages" section
4. Arrange them as needed
5. Save the menu

Suggested menu structure:
```
- Home
- Courses
- Colleges
- Apply Now (Application Form)
- My Dashboard (My Applications)
- Contact
```

## User Workflow

### For Students

1. **Registration**
   - Visit `/student-registration/`
   - Fill registration form
   - Receive welcome email
   - Auto-login after registration

2. **Submit Application**
   - Login and visit `/application-form/`
   - Fill personal information
   - Select course and college
   - Upload required documents
   - Submit application
   - Receive confirmation email with application number

3. **Track Application**
   - Login to dashboard at `/my-applications/`
   - View all applications and their status
   - Download application PDFs
   - Make payments

4. **Check Status (Without Login)**
   - Visit `/application-status/`
   - Enter application number
   - View current status

### For Administrators

1. **View Dashboard**
   - Go to **OneForm → Dashboard**
   - See statistics and recent applications

2. **Manage Applications**
   - Go to **OneForm → Applications**
   - Filter by status
   - Update application status
   - Delete applications
   - Export to CSV

3. **Monitor Payments**
   - Go to **OneForm → Payments**
   - View all transactions
   - Check payment status

## Database Structure

### Tables Created

1. **`wp_ck_oneform_applications`**
   - Stores application submissions
   - Fields: id, user_id, form_id, application_number, status, form_data, dates

2. **`wp_ck_oneform_submissions_meta`**
   - Stores additional application metadata
   - Fields: meta_id, submission_id, meta_key, meta_value

3. **`wp_ck_oneform_payments`**
   - Stores payment records
   - Fields: id, application_id, user_id, transaction_id, amount, currency, status, dates

4. **`wp_ck_oneform_documents`**
   - Stores uploaded document references
   - Fields: id, application_id, user_id, document_type, file_path, dates

## Customization

### Modify Form Fields

Add this to your theme's `functions.php`:

```php
add_filter('ck_oneform_form_fields', 'customize_oneform_fields', 10, 2);
function customize_oneform_fields($fields, $form_id) {
    // Add custom field
    $fields[] = array(
        'name' => 'custom_field',
        'label' => 'Custom Field',
        'type' => 'text',
        'required' => false
    );

    return $fields;
}
```

### Customize Email Templates

Email templates are located in `templates/emails/`. You can override them by copying to your theme:

```
your-theme/
  ck-oneform/
    emails/
      application-confirmation.php
      admin-notification.php
      welcome.php
```

### Add Custom Styles

```php
add_action('wp_enqueue_scripts', 'my_oneform_styles');
function my_oneform_styles() {
    wp_enqueue_style('my-oneform-custom', get_stylesheet_directory_uri() . '/oneform-custom.css');
}
```

## Payment Gateway Integration

### Razorpay Setup

1. Get API keys from [Razorpay Dashboard](https://dashboard.razorpay.com/)
2. Go to **OneForm → Settings**
3. Enable Payments
4. Select "Razorpay"
5. Enter Key ID and Secret Key
6. Save settings

### PayU Setup

1. Get credentials from PayU
2. Configure in settings
3. Test in sandbox mode first

## Security Features

- **Nonce Verification** - All forms use WordPress nonces
- **Data Sanitization** - All inputs are sanitized
- **SQL Injection Prevention** - Prepared statements used
- **File Upload Validation** - File type and size checks
- **User Capability Checks** - Admin functions protected
- **CSRF Protection** - Built-in WordPress security

## Performance

- **Optimized Queries** - Efficient database operations
- **Lazy Loading** - Load resources only when needed
- **Caching Support** - Compatible with caching plugins
- **Minimal Dependencies** - Lightweight codebase

## Troubleshooting

### Pages Not Found (404)

1. Go to **Settings → Permalinks**
2. Click "Save Changes" to flush rewrite rules

### Database Tables Not Created

Deactivate and reactivate the plugin.

### Email Not Sending

1. Check email settings in **OneForm → Settings**
2. Verify SMTP configuration in WordPress
3. Test with WP Mail SMTP plugin

### Uploads Failing

1. Check file permissions on `/wp-content/uploads/`
2. Verify file size limits in `php.ini`
3. Check allowed file types

## Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **MySQL**: 5.6 or higher
- **PHP Extensions**: mysqli, json, mbstring

## Support

For support and questions:
- Email: support@collegekampus.com
- Documentation: https://collegekampus.com/docs/
- GitHub Issues: [Report a bug]

## Changelog

### Version 1.0.0
- Initial release
- Student registration system
- Application form builder
- Dashboard and status tracking
- Payment integration
- Email notifications
- PDF generation
- Admin panel
- Export functionality

## License

GPL v2 or later

## Credits

Developed by CollegeKampus
https://collegekampus.com

## Future Enhancements

Planned features for future versions:
- Multi-language support
- SMS notifications
- Advanced form builder (drag & drop)
- Interview scheduling
- Document verification
- Bulk import students
- Analytics dashboard
- Mobile app integration
- API access
