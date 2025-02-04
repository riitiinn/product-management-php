# PHP Project

## Requirements

Before setting up the project, ensure you have the following installed:

- PHP (>= 7.4 or latest stable version)
- Composer (for dependency management)
- Web server (Apache, Nginx, or built-in PHP server)
- MySQL 

## Installation

1. **Clone the Repository**
   ```sh
   git clone https://github.com/riitiinn/yourproject.git
   cd yourproject
   ```


   ```

3. **Set Up Environment Variables**
   - Copy the `.env.example` file and rename it to `.env`
   - Update necessary configurations such as database connection details
    DB_HOST=localhost
    DB_NAME=<Your db name>
    DB_USER=<Your username>
    DB_PASS=<Your password>



## Running the Project

### Using Built-in PHP Server
```sh
php -S localhost:8000 -t public
```

### Using Apache or Nginx
- Configure your virtual host to point to the `public` directory.
- Restart the web server after making changes.



