# QC Values Migration Documentation

## Overview
This document describes the migration of QC (Quality Control) values from numeric format (0, 1) to string format ('LN', 'DN') across the entire inventory system.

## Problem Statement
The system had inconsistencies in how QC values were handled:
- **Needs system**: Used 'LN' and 'DN' (string format)
- **Inventory system**: Used '0' and '1' (numeric format)
- **Database schema**: Mixed `tinyint(1)` and `varchar(50)` for `dvc_qc` column
- **UI components**: Mixed display and value formats

## Changes Made

### 1. Database Schema Updates
- **inv_needs table**: Changed `dvc_qc` from `tinyint(1)` to `varchar(50)`
- **inv_report table**: Changed `dvc_qc` from `tinyint(1)` to `varchar(50)`
- **inv_act table**: Already using `varchar(50)` (no change needed)

### 2. Code Updates

#### Files Modified:
1. **inv_database.sql**
   - Updated table structure for `inv_needs` and `inv_report`
   - Changed `dvc_qc` column type to `varchar(50)`

2. **application/models/Data_model.php**
   - Updated SQL queries to use 'LN' and 'DN' instead of '0' and '1'
   - Line 185-186: Changed QC value references

3. **application/views/inventory/massive_input.php**
   - Updated select options to use 'LN' and 'DN' as values
   - Line 95-98: Changed option values

4. **application/views/inventory/inv_ecct.php**
   - Already using correct 'LN' and 'DN' values (no change needed)

### 3. Files Already Correct
The following files were already using the correct 'LN' and 'DN' format:
- `application/views/report/needs/report_app_show.php`
- `application/views/report/needs/report_osc_show.php`
- `application/views/report/javascript_report.php`
- `application/controllers/inventory.php` (needs processing)
- `application/models/inventory_model.php` (query references)

## Migration Script

### SQL Migration Script: `update_qc_values.sql`
This script will:
1. Update database schema for both tables
2. Convert existing numeric values to string format
3. Verify the changes

**To run the migration:**
```sql
-- Execute the update_qc_values.sql script in your database
```

## QC Value Mapping

| Old Format | New Format | Description |
|------------|------------|-------------|
| 0          | 'LN'       | Local Needs |
| 1          | 'DN'       | Domestic Needs |

## Validation

### Database Validation
After migration, verify that:
1. All `dvc_qc` columns are `varchar(50)`
2. No numeric values (0, 1) remain in the database
3. Only 'LN' and 'DN' values exist

### Application Validation
1. **Needs Input**: Should accept and display 'LN' and 'DN'
2. **Inventory Input**: Should use 'LN' and 'DN' values
3. **Reports**: Should display 'LN' and 'DN' correctly
4. **Data Export**: Should export with correct QC values

## Testing Checklist

- [ ] Run migration script on test database
- [ ] Verify needs input functionality
- [ ] Verify inventory input functionality  
- [ ] Verify report generation
- [ ] Verify data export functionality
- [ ] Test with existing data
- [ ] Test with new data entry

## Rollback Plan

If issues occur, you can rollback by:
1. Reverting the database schema changes
2. Converting 'LN' back to '0' and 'DN' back to '1'
3. Reverting the code changes

## Notes

- The `all_item.php` file uses a different QC system (Pending/Passed/Failed) and was not modified
- This migration ensures consistency across the needs and inventory systems
- All existing functionality should work the same, just with consistent QC value format
