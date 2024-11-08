# Development Environment Overview
For this project, we will be developing HTML, PHP, JS and CSS scripts within the WheelDeal directory. 
Furthermore, we will be developing and maintaining a MySQL database via the database.sql script.

### Synchronizing changes with MAMP
For the MAMP server to reflect any changes in the WheelDeal directory or to the database, any changes must be synchronized to the MAMP directory.


**Synchronizing WheelDeal folder (HTML, PHP, CSS) to server**
- Open a terminal in the repository folder and run `./pushToServer.sh`.


**Additionally rebuilding server database from database.sql script**
- Run `./pushToServer.sh -s` to rebuild the WheelDeal directory *and* the MySQL database (via database.sql script).
    - Optionall add the `-d` flag to load the database with dummy data specified in dummydata.sql.


*To get all of that to work, follow the following steps to set up the development environment.*

### Setting up the development environment...
**Cloning the repo**
1. Clone this repo to your usual coding directory.

**Installing MAMP**
1. Install and launch MAMP.
2. Launch server in MAMP.
3. Verify that MAMP server works by visiting [localhost:8888](http://localhost:8888).

**Setting up environment in repository**
1. Create .env file in repository.
2. Add HTDOCS_PATH (path to htdocs directory in MAMP folder, e.g. HTDOCS_PATH="/Applications/MAMP/htdocs").
3. Install mysql-connector-python by running `pip3 install -r requirements.txt`
4. Optional: Set custom MySQL host, port, username, password in .env file
    - If you do not configure anything, it defaults to: DB_HOST localhost, PORT 3306, DB_USER root, DB_PASSWORD root
    - If you want to customize, add a line in .env, e.g. `DB_HOST=localhost` or `PORT=8889`


**Test configuration**
- Run `./pushToServer.sh -s` and see if it runs without errors. If yes, check if the[WheelDeal page](http://localhost:8888/WheelDeal) is up and check if you can see the WheelDeal database on [PHPMyAdmin](http://localhost:8889).


If you see these, you are good to go!

### NB: Developers need to download Bootstrap!