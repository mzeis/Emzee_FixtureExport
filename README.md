Emzee_FixtureExport
=====================
Export products in the YAML fixture format required by
[EcomDev_PHPUnit](https://github.com/IvanChepurnyi/EcomDev_PHPUnit).

This is a very early version with many known issues (see the [Known Issues](#known-issues) section)
and likely to break until you're not using very simple data.
Please report (or even fix, yay!) issues that you find while playing around with your data sets.

Facts
-----
- version: 0.1.0
- [extension on GitHub](https://github.com/mzeis/Emzee_FixtureExport)
- [Changelog](CHANGELOG.md)

Compatibility
-------------
- Magento >= CE 1.9 (may also work in other versions)

Installation
------------
1. Install the extension using [Composer](https://getcomposer.org/),
[modman](https://github.com/colinmollenhour/modman) or copy all the
files to the according Magento directories manually.

Uninstallation
--------------
1. Remove the files.

Usage
-----
After installing the extension, create the products you want to export and head over to
`System > Import/Export > Export`. Select the Entity Type `Products` and the Export File Format
`EcomDev_PHPUnit Fixture`. Set the filters as desired and click `Continue` to export the
resulting YAML file.

If you get an error message check the Magento logs for error messages.

All limitations of the Magento Import/Export functionality and EcomDev_PHPUnit fixture apply.
That means that certain product types or parts of the data may not be exportable and importable.

If you have sample fixtures that give more insight on the expected fixture format than the
[EcomDev_PHPUnit manual examples](http://www.ecomdev.org/wp-content/uploads/2011/05/EcomDev_PHPUnit-0.2.0-Manual.pdf)
don't hesitate to share them so that somebody can implement them.

Known Issues
------------

###General
* Multi line values (e.g. for description) aren't implemented

###Complex data
* Configurable products, bundle products etc. haven't been tested and are likely to not work yet

###Product options
* Product options haven't been tested and are likely to not work yet.

###Website-specific data
* Website-specific data isn't implemented

###Prices
* MSRP hasn't been tested / implemented

###Category assignments
* Category names / paths are exported instead of the assigned category ids

###Product images
* Product images aren't implemented

Support
-------
If you have any issues with this extension, open an issue in the GitHub
repository. Please provide error messages, debug information like output
from the Magento error logs and the exact steps / code to reproduce the
issue.

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to
open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------
Matthias Zeis ([matthias-zeis.com](http://www.matthias-zeis.com), [@mzeis](https://twitter.com/mzeis))

Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)

Copyright
---------
(c) 2014 Matthias Zeis