<?php

class database {
    protected mysqli $connection;
    private string $host;
    private string $username;
    private string $password;
    private array|false $db_config;

    /**
     * Constructor for initializing database connection using the provided INI file path.
     *
     * @param string $database_config_ini_path The path to the database config INI file
     * @throws E_USER_ERROR Error triggered if the path is empty
     */
    public function __construct(string $database_config_ini_path = "") {
        // Output error if path is empty
        if($database_config_ini_path == "")
            trigger_error("Database config INI path is empty", E_USER_ERROR);

        $this->db_config = parse_ini_file($database_config_ini_path);

        $this->host = $this->db_config["host"];
        $this->username = $this->db_config["username"];
        $this->password = $this->db_config["password"];
        $this->database = $this->db_config["database"];
    }

    /**
     * Connects to a specified database and returns a boolean based on the connection status.
     *
     * @param string $database The name of the database to connect to.
     * @throws E_USER_ERROR No Database specified or Database `{$database}` not available.
     * @return bool
     */
    public function connect() : bool {
        if(isset($this->connection))
            $this->disconnect();

        if($this->database == "")
            trigger_error("No Database specified", E_USER_ERROR);

        // Connect to the MySQL server
        $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->database);
        return (bool)$this->connection;
    }

    /**
     * Disconnects from the database.
     *
     * @return bool
     */
    public function disconnect(): bool {
        return mysqli_close($this->connection);
    }

    /**
     * Check if the specified database is available in the configuration.
     *
     * @param string $database The name of the database to check.
     * @throws E_USER_ERROR If the database is not available for the specified user.
     * @return bool
     */
    protected function hasDatabase(string $database): bool {
        if(isset($this->db_config[$database]))
            return true;
        trigger_error("The database $database is not available for {$this->db_config['username']}.", E_USER_ERROR);
    }

    /**
     * A function that determines the type of given parameter and returns a corresponding string code.
     *
     * @param mixed $parameter The parameter whose type needs to be determined.
     * @return string The type code ('i' for int, 'd' for double, 's' for string, 'b' for boolean).
     */
    private function getType(mixed $parameter): string {
        if (is_int($parameter))
            return 'i';
        elseif (is_double($parameter))
            return 'd';
        elseif (is_string($parameter))
            return 's';
        return 'b';
    }

    /**
     * Convert special characters in the given parameters and return a formatted string.
     *
     * @param mixed ...$parameters The parameters to convert special characters in.
     * @return mixed The formatted string with converted special characters.
     */
    private function convertSpecialChars(...$parameters): mixed {
        $convertedParameters = array_map(function ($parameter) {
            return htmlspecialchars($parameter, ENT_QUOTES, 'UTF-8');
        }, $parameters);
        return call_user_func_array('sprintf', $convertedParameters);
    }

    /**
     * Execute a prepared SQL query and return the result
     *
     * @param string $sql The prepared SQL query to execute
     * @param mixed ...$parameters The parameters to bind to the prepared query
     * @return mysqli_result|bool The result of the query, or false if the query failed
     */
    protected function doQueryPrepared(string $sql, ...$parameters): bool|mysqli_result {
        if(!isset($this->connection))
            return false;

        // Create an array of types for the parameters
        $types = '';
        foreach ($parameters as $parameter) {
            $types .= $this->getType($parameter);
        }

        // Prepare the statement
        $statement = $this->connection->prepare($sql);

        // If an Error occurs, the statement couldn't prepare the SQL Code
        if (gettype($statement == "boolean") and !$statement)
            return false;

        // Bind the parameters to the statement
        if (!empty($types))
            $statement->bind_param($types, ...$parameters);

        // Execute the statement
        $result = $statement->execute();

        // Check if the statement is a statement that returns a `mysqli_result`
        // The if statement can be expanded with required statements
        $result = str_starts_with($sql, 'SELECT') || str_starts_with($sql, 'SHOW') ? $statement->get_result() : $statement->affected_rows > 0;

        // Close the statement
        $statement->close();
        return $result;
    }
}
