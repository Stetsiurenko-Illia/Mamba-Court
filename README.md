# 🏀 Mamba Court — Custom WooCommerce Theme

![WordPress](https://img.shields.io/badge/WordPress-21759B?style=for-the-badge&logo=wordpress&logoColor=white)
![WooCommerce](https://img.shields.io/badge/WooCommerce-96588A?style=for-the-badge&logo=woocommerce&logoColor=white)
![Sass](https://img.shields.io/badge/Sass-CC6699?style=for-the-badge&logo=sass&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

**Mamba Court** is a fully custom, high-performance eCommerce theme built from scratch for WordPress and WooCommerce. Designed with a focus on clean UI/UX, responsive architecture, and a modern "Mamba Mentality" basketball aesthetic.

🔗 **[Live Demo (Pantheon)](https://dev-mamba-court.pantheonsite.io/)**

---

## 📸 Preview
*(Add a screenshot of your homepage here. E.g., `![Homepage](./images/screenshot.png)`)*

## ✨ Key Features

- **Custom SCSS Architecture:** Modular, easily maintainable styling using SCSS partials, variables, and nesting. Compiled into a single minified `style.css` for optimal performance.
- **Advanced WooCommerce Integration:** Deep overrides of default WooCommerce templates. Fully customized Cart and Checkout Gutenberg blocks for a seamless conversion funnel.
- **Smart Responsive Design:** Fluid grid layouts tailored for all devices. Custom "Bottom Sheet" mobile filters and optimized tablet (`1024px`) breakpoints to eliminate the "tablet gap".
- **Dynamic Filtering & Sorting:** Integrated sidebar product filters with custom toggle switches, chips for active filters, and an optimized mobile overlay.
- **Interactive Search:** Custom AJAX search bar dynamically repositioned across devices for best UX.
- **eCommerce Enhancements:** Integrated Wishlist, Brand carousels, Custom Size Charts, and visual Variation Swatches.

## 🏗️ Folder Structure (SCSS)

The theme's styling is completely driven by a modern SCSS architecture:
```text
assets/scss/
├── _variables.scss    # Theme colors and global variables
├── _global.scss       # Resets, typography, and base elements
├── _header.scss       # Navigation, Burger menu, AJAX Search
├── _footer.scss       # Footer layout and links
├── _home.scss         # Hero section, Brand sliders
├── _catalog.scss      # Shop grid, Sidebar filters, Mobile sorting
├── _product.scss      # Single product layout, galleries, swatches
├── _checkout.scss     # Custom Woo Blocks (Cart & Checkout)
├── _account.scss      # My Account dashboard and login forms
├── _wishlist.scss     # Wishlist tables and header counters
└── style.scss         # Main compiler file
