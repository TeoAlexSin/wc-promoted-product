Create a plugin to display a promoted product

The goal is to feature an individual product as "promoted."
Anything that is a "promoted" product has to be visible on every page and always shown in the same way.
The displayed product also needs to have a link to the actual product.

*The plugin should add a new section under the "WooCommerce > Settings > Products" tab and must include the following fields:**
1) a text input field for the title of the promoted product (e.g. "FLASH SALE:")
2) a color picker for the background color
3) a color picker for the text color
4) a display of the active promoted product title and a link to edit that product

In the single product editor, add following fields in the general tab:
1) A checkbox with the label "Promote this product" that activates this product as "promoted" when checked
2) A text field containing a custom title that will be shown instead of the product title (if empty, display the product title)
3) A checkbox to set an expiration date and time. If checked, add an option to select the date and time. When the required time expires, the product should not be marked as "promoted" anymore.

Front-end instructions:
If there is a product set as "promoted," display a full width div at the bottom of the site's header with this format:

[Promoted title from backend]: [product title \| custom title]
There can be only one active promoted product at a time, which should be the product that was activated last.

Please use a public Github repository to version-control your code and send us the link for us to review.

Bonus points:
• Ensure the plugin is written in object-oritented code
• Use Composer Autoloader and follow PSR-4 standard
• Ensure the plugin follows WordPress coding standards
• Ensure plugin is performant and leverage caching when applicable
• Use PHP DocBlock where applicable
