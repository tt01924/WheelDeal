import mysql.connector
import os

try:
    # Connect to the MySQL database
    host = os.getenv('DB_HOST', 'localhost')
    user = os.getenv('DB_USER', 'root')
    password = os.getenv('DB_PASSWORD', 'root')
    port = os.getenv('DB_PORT', 8889)

    print("Connecting to MySQL...")

    db_connection = mysql.connector.connect(
        host=host,
        user=user,
        password=password,
        port=port
    )
    cursor = db_connection.cursor()

    print("Reading database.sql...")
    # Read SQL script
    with open("database.sql", "r") as sql_file:
        sql_script = sql_file.read()

    print("Executing database.sql...")
    # Execute each statement in the script
    for statement in sql_script.split(";"):
        if statement.strip():
            cursor.execute(statement)

    print("Commiting SQL changes...")
    # Commit changes
    db_connection.commit()

    print("Success rebuilding database!")

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