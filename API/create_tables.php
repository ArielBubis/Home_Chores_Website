<?php

// Query: Create Users table 
function create_users_table($conn)
{
    $sql = "CREATE TABLE Users(
            user_id INT AUTO_INCREMENT PRIMARY KEY, 
            email VARCHAR(255),
            first_name VARCHAR(255) NOT NULL,
            last_name VARCHAR(255) NOT NULL,
            avatar_color VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
        );";
    if ($conn->query($sql)) {
        echo "The table 'Users' created successfully <br>";
    } else {
        echo "Error in creating 'Users' table: " . $conn->error . "<br>";
    }
}

// Query: Create Household table 
function create_household_table($conn)
{
    $sql = "CREATE TABLE Household(
                house_id INT AUTO_INCREMENT PRIMARY KEY,
                responsible_user_id INT,
                FOREIGN KEY (responsible_user_id) REFERENCES Users(user_id) ON DELETE CASCADE
            );";
    if ($conn->query($sql)) {
        echo "The table 'Household' created successfully <br>";
    } else {
        echo "Error in creating 'Household' table: " . $conn->error . "<br>";
    }
}

// Query: Create Chorses_List table 
function create_chores_list_table($conn)
{
    $sql = "CREATE TABLE Chores_List(
            list_id INT AUTO_INCREMENT PRIMARY KEY,
            house_id INT,
            responsible_user_id INT,
            list_title VARCHAR(255) NOT NULL,
            due_date DATE,
            status BOOLEAN NOT NULL DEFAULT 0,
            FOREIGN KEY (house_id) REFERENCES Household(house_id) ON DELETE CASCADE,
            FOREIGN KEY (responsible_user_id) REFERENCES Users(user_id) ON DELETE CASCADE
        );";
    if ($conn->query($sql)) {
        echo "The table 'Chores_List' created successfully <br>";
    } else {
        echo "Error in creating 'Chores_List' table: " . $conn->error . "<br>";
    }
}


// Query: Create Users_partOf_Household table 
function users_partof_household($conn)
{
    $sql = "CREATE TABLE Users_partOf_Household(
                user_id INT,
                house_id INT,
                FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
                FOREIGN KEY (house_id) REFERENCES Household(house_id) ON DELETE CASCADE,
                PRIMARY KEY (user_id, house_id)
                    );";
    if ($conn->query($sql)) {
        echo "The table 'Users_partOf_Household' created successfully <br>";
    } else {
        echo "Error in creating 'Household' table: " . $conn->error . "<br>";
    }
}


// Query: Create chores table
function create_chrores_table($conn)
{
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
    if ($conn->query($sql)) {
        echo "The table 'Chores' created successfully <br>";
    } else {
        echo "Error in creating 'Chores' table: " . $conn->error . "<br>";
    }
}


