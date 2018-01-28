# Capybara Indexer (Capdex.php)
Index your **ALL** folder with just one file... yes, one file. Demo is located on [pbw.christianto.net](https://pbw.christianto.net)

# Requirements
- PHP 7 or later
- Web Server

# Getting Started
1. Put our `index.php` file in the `dist` folder, into the root folder.
2. Done. Wait, just make sure that the `index.php` is accessible and you're good to go.

# How about the security?
- We wont show dot files (`.git/`, `.htaccess`, `.noindex`, `.chez14-is-too-fabulous`, etc).
- We wont show `cgi_bin` folders.
- **WARNING :** We will keep showing the folders that you've settings the `.htaccess` to forbidden. (will be the next step to secure this thing)
- make a `.noindex` file inside your secret folder to hide **the folder** from the indexing.
- we wont allow the `./` and `../` query for browsing the folder.
- and, yep, we don't accept file upload.

# How is it works?
We list all files in the directory, then make a quick checkups, then serve the list to viewer with this php as a *viewer* link. That's it.

# How to set the settings?
The settings are available on the toppest region of this `index.php` file.