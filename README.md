# LOVEnDO - E-commerce Platform

A WordPress-based e-commerce platform that facilitates both traditional online store sales and peer-to-peer second-hand item transactions.

## Table of Contents
- [Introduction](#introduction)
- [University Project](#university-project)
- [Installation](#installation)
- [Usage](#usage)
- [Technologies Used](#technologies-used)
- [Contributing](#contributing)
- [License](#license)

## Introduction

LOVEnDO is an e-commerce platform developed as part of a university project. The system allows users to browse and purchase products from a virtual store, as well as buy and sell second-hand items through a dedicated marketplace.

## University Project
This project was developed as part of the coursework for "Gestión de Contenidos Multimedia" from the Multimedia Engineering Degree at the University of Alicante. The main objectives include:
- Implementing an online store using WordPress and WooCommerce.
- Creating a custom post type for peer-to-peer product listings.
- Developing custom plugins for tracking product views and integrating Wikidata queries.

## Installation

To set up the project on your local machine, follow these steps:

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/your-repo.git
   ```
2. Set up a local server using XAMPP, MAMP, or another similar tool.
3. Import the provided database dump via phpMyAdmin.
4. Place the project files in the appropriate web directory (e.g., `htdocs` for XAMPP).
5. Update the `wp-config.php` file with your database credentials.
6. Start your local server and access the site via `http://localhost/LOVEnDO`.

## Usage

### Online Store
- Users can browse categories, search for products, and add items to their cart.
- Checkout process includes order summary and simulated payment integration.
- A suggestion box allows registered users to submit feedback.

### Peer-to-Peer Marketplace
- Registered users with the "Vendor" role can list items for sale.
- Items include an image, name, description, price, and status (available, reserved, sold).
- A custom plugin tracks and displays product view counts.
- Users can browse second-hand products sorted by popularity or availability.
- Buyers can reserve items, notifying the seller via email.

### Wikidata Integration
- A custom plugin enables users to search Wikidata for information related to the project’s theme.
- Results are displayed dynamically on the website.

## Technologies Used

- WordPress
- WooCommerce
- PHP
- MySQL
- HTML, CSS, JavaScript
- Custom WordPress plugins

## Contributing

If you wish to contribute to this project, follow these steps:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Commit your changes (`git commit -m "Add new feature"`).
4. Push to the branch (`git push origin feature-branch`).
5. Open a Pull Request.

## License

This project is licensed under the MIT License. See the LICENSE file for more details.
---

This document is subject to modifications as the project evolves.

