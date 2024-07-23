<?php

    // Query: Create Users table 
    function create_users_table($conn) { 
        $sql = "CREATE TABLE Users(
            user_id INT AUTO_INCREMENT PRIMARY KEY, 
            email VARCHAR(255),
            first_name VARCHAR(255) NOT NULL,
            last_name VARCHAR(255) NOT NULL,
            avatar_color VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
        );";
        if($conn->query($sql)) {
            echo "The table 'Users' created successfully <br>";
        }
        else {
            echo "Error in creating 'Users' table: " . $conn->error . "<br>";
        }
    }
    
    // Query: Create Chorses_List table 
    function create_chores_list_table($conn) {    
        $sql = "CREATE TABLE Chores_List(
            list_id INT AUTO_INCREMENT PRIMARY KEY,
            list_title VARCHAR(255) NOT NULL,
            due_date DATE,
            status BOOLEAN NOT NULL DEFAULT 0
        );";
        if($conn->query($sql)) {
            echo "The table 'Chores_List' created successfully <br>";
        }
        else {
            echo "Error in creating 'Chores_List' table: " . $conn->error . "<br>";
        }
    }

    // Query: Create chores table
    function create_chrores_table($conn) {
        /* ---- Important ----
                A chore is specific to chores list, therefor we defined chores as a weak entity.
                The key of this table will be list_id + chore_num as in accordance to normalization rules.
            */
        $sql = "CREATE TABLE Chores(
            chore_num INT AUTO_INCREMENT, /* distinguish attribute */
            list_id INT,
            chore_title VARCHAR(255) NOT NULL,
            date_added DATE NOT NULL,
            user_id INT, 
            finished BOOLEAN NOT NULL DEFAULT 0,
            FOREIGN KEY (user_id) REFERENCES Users(user_id),
            FOREIGN KEY (list_id) REFERENCES Chores_List(list_id) ON DELETE CASCADE,
            PRIMARY KEY (chore_num, list_id)
        );";
        if($conn->query($sql)) {
            echo "The table 'Chores' created successfully <br>";
        }
        else {
            echo "Error in creating 'Chores' table: " . $conn->error . "<br>";
        }
    }

    // Query: Create Responsible_for_list table
    function create_responsible_for_list_table($conn) {
        // Note: We assume that we can have more than one responsible user for chores list, based on the option to share the list between different users (which will be handled in the next assigment)
        $sql = "CREATE TABLE Responsible_For_List (
            user_id INT,
            list_id INT,
            FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
            FOREIGN KEY (list_id) REFERENCES Chores_List(list_id) ON DELETE CASCADE,
            PRIMARY KEY (user_id, list_id)
        );";
        if ($conn->query($sql) === TRUE) {
            echo "The table 'Responsible_for_list' created successfully <br>";
        } else {
            echo "Error in creating 'Responsible_For_List' table: " . $conn->error . "<br>";
        }

    }

    

