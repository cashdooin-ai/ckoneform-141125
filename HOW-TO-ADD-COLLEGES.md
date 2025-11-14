# How to Add 500+ Colleges to CollegeKampus OneForm

This guide explains how to add colleges to your OneForm plugin.

## Method 1: Using CSV Import (Recommended for Bulk Upload)

### Step 1: Prepare Your CSV File

Create a CSV file with the following columns:

```csv
name,location,type,category,ranking,description
```

**Example:**
```csv
name,location,type,category,ranking,description
"IIT Delhi","New Delhi, Delhi",government,engineering,1,"Premier engineering institute"
"IIT Bombay","Mumbai, Maharashtra",government,engineering,2,"Top technical institute"
```

### Column Descriptions:

- **name**: Full name of the college (required)
- **location**: City, State format (e.g., "Mumbai, Maharashtra")
- **type**: government or private
- **category**: engineering, medical, management, university, arts, commerce, science
- **ranking**: Numeric ranking (optional)
- **description**: Brief description of the college

### Step 2: Upload and Import

#### Option A: Via WordPress Admin

1. **Install Import Plugin:**
   ```
   WordPress Admin → Plugins → Add New
   Search for "WP All Import"
   Install and Activate
   ```

2. **Import Colleges:**
   ```
   WP All Import → New Import
   Upload your CSV file
   Select "CK Colleges" as post type
   Map CSV columns to WordPress fields
   Run the import
   ```

#### Option B: Via PHP Script (Provided)

1. **Upload CSV:**
   - Upload `colleges-sample.csv` to your plugin folder
   - Or create your own CSV with 500 colleges

2. **Run Import Script:**
   ```php
   // Access this URL (admin only):
   https://yoursite.com/wp-content/plugins/collegekampus-oneform/import-colleges.php?import_colleges=1

   // Or via WP-CLI:
   wp eval-file import-colleges.php
   ```

3. **The script will:**
   - Read your CSV file
   - Create college posts
   - Add all metadata
   - Skip duplicates
   - Show import results

## Method 2: Manual Entry (For Few Colleges)

### Via WordPress Admin:

1. Go to **OneForm → Colleges → Add New**

2. Enter college details:
   - **Title**: College name (e.g., "IIT Delhi")
   - **Content**: Detailed description
   - **Featured Image**: Upload college logo/image

3. **Custom Fields** (add these in the post):
   - Location: New Delhi, Delhi
   - Type: government/private
   - Category: engineering/medical/management
   - Ranking: 1, 2, 3, etc.

4. Click **Publish**

## Method 3: Copy & Paste from PHP Array

### Edit `import-colleges.php`:

Add more colleges to the array:

```php
array(
    'name' => 'Your College Name',
    'location' => 'City, State',
    'type' => 'government', // or 'private'
    'category' => 'engineering', // or 'medical', 'management', etc.
    'ranking' => 100,
    'courses' => array('B.Tech', 'M.Tech'),
    'description' => 'College description here',
),
```

Run the script to import.

## Getting List of Top 500 Colleges

### Sources for College Data:

1. **NIRF Rankings:**
   - Visit: https://www.nirfindia.org/
   - Download college rankings
   - Convert to CSV format

2. **College Websites:**
   - Many aggregate sites list colleges
   - collegekampus.com may already have this data
   - Copy and format into CSV

3. **Government Sources:**
   - UGC website
   - AICTE approved colleges list
   - State education department websites

### Sample Data Sources:

```
Engineering: NITs, IITs, IIITs, State Engineering Colleges
Medical: AIIMS, Government Medical Colleges, Private Medical
Management: IIMs, Top B-Schools
Arts/Science: Central Universities, State Universities
```

## Creating Complete CSV Template

### Template Structure:

```csv
name,location,type,category,ranking,description,website,established,affiliation
"IIT Delhi","New Delhi, Delhi",government,engineering,1,"Premier institute",https://iitd.ac.in,1961,Autonomous
```

### Extended Fields (Optional):

You can add more columns:
- **website**: College website URL
- **established**: Year established
- **affiliation**: University affiliation
- **accreditation**: NAAC/NBA rating
- **fees**: Annual fees
- **seats**: Total seats
- **courses**: Comma-separated list
- **entrance_exam**: JEE/NEET/CAT etc.

## Automated Import from Your Existing Data

If you have colleges data elsewhere:

### From MySQL Database:

```sql
SELECT
    college_name as name,
    CONCAT(city, ', ', state) as location,
    college_type as type,
    category,
    ranking,
    description
FROM your_colleges_table
INTO OUTFILE '/tmp/colleges.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n';
```

### From Excel:

1. Open your Excel file
2. Format columns as shown above
3. Save As → CSV (Comma delimited)
4. Import using method above

## Verifying Import

After import, check:

1. **Total Colleges:**
   ```
   OneForm → Colleges
   Check the count at top
   ```

2. **Test Search:**
   - Go to application form
   - Search for colleges
   - Verify they appear

3. **Check Filters:**
   - Filter by Government/Private
   - Filter by Category
   - Verify correct colleges show

## Sample Data Provided

I've included **40 sample colleges** in:
- `colleges-sample.csv` - Ready to import
- `import-colleges.php` - Edit to add more

### To expand to 500:

1. **Copy the pattern:**
   ```csv
   "College Name","City, State",type,category,ranking,"Description"
   ```

2. **Add more rows:**
   - Research online for college names
   - Use NIRF rankings
   - Add state universities
   - Include private colleges
   - Add specialized institutes

3. **Organize by:**
   - State (for better UX)
   - Type (govt/private)
   - Category (engineering/medical/etc.)

## Updating Existing Colleges

To update instead of adding new:

```php
// In import-colleges.php, modify the check:
$existing = get_page_by_title($college['name'], OBJECT, 'ck_college');

if ($existing) {
    // Update instead of skip
    wp_update_post(array(
        'ID' => $existing->ID,
        'post_content' => $college['description']
    ));
    // Update meta fields
}
```

## Bulk Actions

### Delete All Colleges:

```sql
-- WARNING: This deletes ALL colleges
DELETE FROM wp_posts WHERE post_type = 'ck_college';
DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT ID FROM wp_posts);
```

### Export Existing Colleges:

```php
// Add this function to export to CSV
$colleges = get_posts(array('post_type' => 'ck_college', 'posts_per_page' => -1));
// Create CSV from $colleges
```

## Need Help?

If you need help with:
- Getting 500 colleges data
- Formatting CSV
- Running import
- Custom fields

Just provide:
1. Where you have the data (website, database, Excel)
2. Format it's currently in
3. Any specific requirements

I can help create the import script!

---

## Quick Start Checklist

- [ ] Download/create colleges CSV file
- [ ] Upload to plugin folder
- [ ] Run import script
- [ ] Verify colleges imported
- [ ] Test on application form
- [ ] Check filters work
- [ ] Verify search functionality

**Once you share screenshots of the OneForm design, I'll update the homepage template to match exactly!**
