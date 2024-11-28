"""
* File: database_setup.py
* Purpose: Setup and initialize MySQL database with optional dummy data
* Dependencies: mysql-connector-python, .env file, database.sql, dummydata.sql
* Flow: Load environment variables -> Connect to MySQL -> Execute SQL scripts -> Close connection
"""
import mysql.connector
import os
import sys

try:
    # Check if dummy data should be loaded from command line argument
    toggledummydata = '--toggleDummyData' in sys.argv

    # Load database configuration from .env file
    # Creates dictionary from key=value pairs in .env file
    os.environ.update({key: value for key, value in (line.split('=') for line in open('.env') if line.strip())})
    # Connect to the MySQL database
    host = os.getenv('DB_HOST', 'localhost')
    user = os.getenv('DB_USER', 'root')
    password = os.getenv('DB_PASSWORD', 'root')
    port = os.getenv('DB_PORT', 3306)
    
    print("Connecting to MySQL...")

    # Establish database connection
    db_connection = mysql.connector.connect(
        host=host,
        user=user,
        password=password,
        port=port
    )
    cursor = db_connection.cursor()

    def readExecuteCommit(sqlfile):
        """
           Reads, executes, and commits SQL statements from a file
           Args:
               sqlfile (str): Path to SQL file to execute
           """
        print(f"Reading {sqlfile}...")
        # Read SQL script
        with open(sqlfile, "r") as sql_file:
            sql_script = sql_file.read()

        print(f"Executing {sqlfile}...")
        # Execute each statement in the script
        for statement in sql_script.split(";"):
            if statement.strip():
                cursor.execute(statement)

        print(f"Committing SQL changes of {sqlfile}...")
        # Commit changes
        db_connection.commit()

    # Execute main database setup script
    readExecuteCommit('database.sql')
    if toggledummydata:
        readExecuteCommit('dummydata.sql')

    print("Success rebuilding database!")

# Handle errors
except mysql.connector.Error as err:
    print(f"Error: {err}")
except FileNotFoundError:
    print("Error: The SQL script file was not found.")
except Exception as e:
    print(f"An unexpected error occurred: {e}")
finally:
    print("Closing connection.")
    # Close the connection
    if 'cursor' in locals():
        cursor.close()
    if 'db_connection' in locals():
        db_connection.close()