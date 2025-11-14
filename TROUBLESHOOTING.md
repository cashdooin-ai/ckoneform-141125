# OneForm Plugin Troubleshooting Guide

## White Page / Blank Page Issue

If you're seeing a white or blank page, follow these steps:

### Step 1: Enable WordPress Debug Mode

Edit `wp-config.php` and add:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Check `/wp-content/debug.log` for errors.

### Step 2: Check Plugin Activation

1. Go to **WordPress Admin → Plugins**
2. Make sure "CollegeKampus OneForm" is **Activated**
3. If not, activate it
4. If activation fails, check the error message

### Step 3: Verify Pages Were Created

1. Go to **Pages → All Pages**
2. Look for these pages:
   - OneForm Home
   - Student Registration
   - Application Form
   - My Applications
   - Application Status
   - Payment

3. If missing, deactivate and reactivate the plugin

### Step 4: Check Permalinks

1. Go to **Settings → Permalinks**
2. Click **Save Changes** (this flushes rewrite rules)
3. Try accessing the page again

### Step 5: Check Theme Compatibility

Try switching to a default WordPress theme (Twenty Twenty-Three):

1. **Appearance → Themes**
2. Activate Twenty Twenty-Three
3. Test the OneForm page again

### Step 6: Check for Plugin Conflicts

1. Deactivate all other plugins
2. Test OneForm
3. Reactivate plugins one by one to find conflicts

### Step 7: Verify File Permissions

```bash
# All folders should be 755
find /path/to/wp-content/plugins/collegekampus-oneform -type d -exec chmod 755 {} \;

# All files should be 644
find /path/to/wp-content/plugins/collegekampus-oneform -type f -exec chmod 644 {} \;
```

### Step 8: Check PHP Version

OneForm requires:
- **PHP 7.4 or higher**
- **MySQL 5.6 or higher**

Check your PHP version:
```bash
php -v
```

### Step 9: Check Memory Limit

Add to `wp-config.php`:
```php
define('WP_MEMORY_LIMIT', '256M');
```

### Step 10: Use Debug Template

The plugin now includes a debug template. If you see "OneForm Homepage - Debug Mode", the plugin is working and the issue is with CSS/JavaScript.

---

## Common Issues & Solutions

### Issue: "Template file not found"

**Solution:**
```bash
# Verify files exist
ls -la wp-content/plugins/collegekampus-oneform/templates/frontend/
```

Should show:
- home.php
- home-modern.php
- home-debug.php
- application-form.php
- etc.

### Issue: Database tables not created

**Solution:**
1. Deactivate plugin
2. Check database for existing tables:
   - wp_ck_oneform_applications
   - wp_ck_oneform_submissions_meta
   - wp_ck_oneform_payments
   - wp_ck_oneform_documents
3. If tables exist but corrupted, drop them
4. Reactivate plugin

### Issue: CSS not loading

**Check:**
1. View page source (Ctrl+U)
2. Look for:
   ```html
   <link rel='stylesheet' id='ck-oneform-frontend-css' href='.../assets/css/frontend.css' />
   <link rel='stylesheet' id='ck-oneform-home-modern-css' href='.../assets/css/home-modern.css' />
   ```
3. If missing, the enqueue function isn't working

**Solution:**
Clear cache:
- WordPress cache
- Browser cache
- CDN cache (if using)

### Issue: JavaScript not working

**Check browser console** (F12):
- Look for JavaScript errors
- Check if jQuery is loaded

### Issue: Shortcode showing as text

If you see `[ck_oneform_home]` as text:

**Problem:** Shortcode not registered

**Solution:**
1. Check if plugin is activated
2. Verify includes/class-shortcodes.php exists
3. Check for PHP errors in debug.log

---

## Testing Checklist

Run through this checklist:

- [ ] Plugin activated
- [ ] PHP 7.4+ installed
- [ ] Permalinks flushed
- [ ] Pages created
- [ ] Debug mode enabled
- [ ] Error log checked
- [ ] Theme compatible
- [ ] No plugin conflicts
- [ ] File permissions correct
- [ ] Database tables exist

---

## Getting the Error Log

### Via FTP/SSH:
```bash
tail -f /path/to/wp-content/debug.log
```

### Via WordPress Admin:
Install "WP Log Viewer" plugin

### Via cPanel:
File Manager → wp-content → debug.log

---

## Still Not Working?

### Export Debug Information

Add this to your page temporarily:

```php
<?php
phpinfo();
?>
```

Send the output to support.

### Check Hostinger Specific

Hostinger Cloud Enterprise:
- PHP version set to 7.4+?
- Memory limit adequate?
- ionCube loader enabled?
- All extensions installed?

---

## Quick Fixes

### Fix 1: Force Debug Template

Edit `includes/class-shortcodes.php`:
```php
public static function home_page($atts) {
    $template = 'templates/frontend/home-debug.php';
    ob_start();
    include CK_ONEFORM_PLUGIN_DIR . $template;
    return ob_get_clean();
}
```

### Fix 2: Regenerate Pages

Run this in WordPress admin (Tools → Site Health → Info → Copy site info):

```php
// Or add to functions.php temporarily
add_action('init', 'recreate_oneform_pages');
function recreate_oneform_pages() {
    // Re-run page creation
    // (Copy from activation function)
}
```

### Fix 3: Clear All Caches

```bash
# WordPress
wp cache flush

# Hostinger
# Clear via hPanel → Websites → Manage → Advanced → Clear Cache

# Browser
# Ctrl+Shift+Delete → Clear browsing data
```

---

## Contact Support

If none of these work, provide:

1. PHP version
2. WordPress version
3. Active theme name
4. List of active plugins
5. Contents of debug.log
6. Screenshot of the white page
7. Browser console errors (F12)

---

## Developer Debug

```php
// Add to wp-config.php
define('SCRIPT_DEBUG', true);

// This loads unminified JS/CSS for debugging
```

## Database Check

```sql
-- Run in phpMyAdmin
SHOW TABLES LIKE 'wp_ck_oneform%';

-- Should return 4 tables
-- If 0, plugin activation failed
```

---

**Last Updated:** November 14, 2025
