# WP Custom Template Dependencies
## About author and license
**Plugin's author**: Sergey Khomenko

**License**: Plugin is absolutely free to use, for all, who will need this.

## Documentation
**WP Custom Template Dependencies** get you possibility to create meta-keys, what do you want, for your page templates. Each rule contains _Rule Name_, _Template Name_, _Name of Meta Key_ and _Default key's value_.

**_Rule Name_**: Simple name, which will informate you about rule purpose.

**_Template Name_**: Template file name relatively index.php of your theme. 

>**For example**: If your template file is _.../wp-content/theme/my-creative-theme/pages/unusual-page.php_ you will put in this field only "_pages/unusual-page.php_"

**_Name of Meta Key_**: Meta key's name.

**_Default key's value_**: Default value for this meta-key.

>**For example**: If your key's name is "*my_custom_meta*" and default value is "*custom meta value*", then you will create pair my\_custom\_meta = 'custom meta value'

## Changelog
**0.2.0** - _Auto-configuring metas for each rule_
 - Creating metas for each page, when you create rule
 - Deleting metas, when you removing rule
> Be care. Plugin will not delete meta-keys with non-default values. Remember it, when you add keys again.

**0.1.0** - _First public version_
 - Creating rules
 - Removing rules
 - Configuring default values for meta-keys
