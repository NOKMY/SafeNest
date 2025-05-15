<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

class FirebaseService {
    private $database;


    public function __construct() {
        try {
            $credentialsPath = dirname(__DIR__) . '/config/firebase-credentials.json';
            if (!file_exists($credentialsPath)) {
                throw new \Exception("Credentials file not found at: " . $credentialsPath);
            }

            error_log("Loading credentials from: " . $credentialsPath);
            
            $factory = (new Factory)
                ->withServiceAccount($credentialsPath)
                ->withDatabaseUri('https://safenest-database-default-rtdb.asia-southeast1.firebasedatabase.app/');

            $this->database = $factory->createDatabase();
        } catch (\Exception $e) {
            error_log('Firebase initialization error: ' . $e->getMessage());
            throw $e;
        }
    }


    public function updateSensorConfig($config) {
        try {
            $reference = $this->database->getReference('SensorConfig');
            $reference->update([
                'High' => (float) $config['High'],
                'Medium' => (float) $config['Medium'],
                'Low' => (float) $config['Low'],
                'SensorHeight' => (float) $config['SensorHeight']
            ]);
            return true;
        } catch (\Exception $e) {
            error_log('Error updating sensor config: ' . $e->getMessage());
            return false;
        }
    }


    public function getWaterLevelData() {
        $reference = $this->database->getReference('WaterLevel');
        return $reference->getValue();
    }
    public function getSensorStatus() {
        $reference = $this->database->getReference('SensorStatus');
        return $reference->getValue();
    }

    public function getSensorConfig() {
        $reference = $this->database->getReference('SensorConfig');
        return $reference->getValue();
    }
    public function getHistoricalRecords() {
        $reference = $this->database->getReference('HistoricalRecords');
        return $reference->getValue();
    }

    public function getWaterIndicator() {
        $reference = $this->database->getReference('WaterIndicator');
        return $reference->getValue();
    }

    public function getHistoricalReadings() {
        $reference = $this->database->getReference('HistoricalReadings');
        return $reference->getValue();
    }
    public function getDailyRecords() {
        $reference = $this->database->getReference('DailyRecords');
        return $reference->getValue();
    }

    public function getPast24Hours() {
        $reference = $this->database->getReference('Past24Hours');
        return $reference->getValue();
    }


public function setMessageSwitch($value) {
    try {
        // Ensure the value is either 0 or 1
        $value = ($value == 1) ? 1 : 0;
        
        // Get a reference to the messageswitch node
        $reference = $this->database->getReference('messageswitch');
        
        // Set the value
        $reference->set($value);
        
        return true;
    } catch (\Exception $e) {
        error_log('Error setting messageswitch: ' . $e->getMessage());
        return false;
    }
}

public function getMessageSwitch() {
    try {
        $reference = $this->database->getReference('messageswitch');
        return $reference->getValue();
    } catch (\Exception $e) {
        error_log('Error getting messageswitch: ' . $e->getMessage());
        return null;
    }
}


    public function createUser($user) {
        try {
            // Get a reference to the counter node
            $counterRef = $this->database->getReference('user_counter');
            
            // Get the current counter value
            $currentValue = $counterRef->getValue();
            $newUserId = ($currentValue === null) ? 1 : $currentValue + 1;
    
            // Update the counter value
            $counterRef->set($newUserId);
    
            // Create a new user node with the incremented user_id
            $reference = $this->database->getReference('users/' . $newUserId);
            $reference->set([
                'user_id' => $newUserId,
                'fname' => $user['fname'],
                'lname' => $user['lname'],
                'middle' => $user['middle'],
                'email' => $user['email'],
                'roles' => $user['roles'],
                'password' => password_hash($user['password'], PASSWORD_DEFAULT),
                'username' => $user['username'],
                'status' => $user['status'],
                'address' => $user['address'] ?? '',
                'contact' => $user['contact'] ?? '',
                'avatar' => $user['avatar'] ?? '',
                'code' => $user['code'] ?? '',
                'otp_code' => $user['otp_code'] ?? '',
                'mobile_number' => $user['mobile_number'] ?? '',
                'lock_status' => $user['lock_status'] ?? 'none'
            ]);
            return true;
        } catch (\Exception $e) {
            error_log('Error creating user: ' . $e->getMessage());
            return false;
        }
    }

    public function checkEmailExists($email) {
        try {
            $reference = $this->database->getReference('users');
            $users = $reference->getValue();
            
            if ($users) {
                foreach ($users as $user) {
                    if ($user['email'] === $email) {
                        return true;
                    }
                }
            }
            return false;
        } catch (\Exception $e) {
            error_log('Error checking email: ' . $e->getMessage());
            return false;
        }
    }

    public function getUsersCount() {
        try {
            $reference = $this->database->getReference('users');
            $users = $reference->getValue();
            
            // Check if users is an array and filter out any null values
            if (is_array($users)) {
                $users = array_filter($users, function($user) {
                    return $user !== null;
                });
                return count($users);
            }
            return 0;
        } catch (\Exception $e) {
            error_log('Error getting users count: ' . $e->getMessage());
            return 0;
        }
    }

    public function getAllUsers() {
        try {
            $reference = $this->database->getReference('users/');
            $users = $reference->getValue();
            
            // Check if users is an array and filter out any null values
            if (is_array($users)) {
                $users = array_filter($users, function($user) {
                    return $user !== null;
                });
            }
    
            return $users;
        } catch (\Exception $e) {
            error_log('Error fetching users: ' . $e->getMessage());
            return null;
        }
    }

    public function verifyUserLogin($email, $password) {
        try {
            $reference = $this->database->getReference('users/');
            $users = $reference->getValue();
            
            if (is_array($users)) {
                foreach ($users as $user) {
                    // Skip null values
                    if ($user === null) {
                        continue;
                    }
                    
                    if ($user['email'] === $email) {
                        if (password_verify($password, $user['password'])) {
                            return [
                                'status' => 'success',
                                'user' => $user
                            ];
                        }
                        return [
                            'status' => 'error',
                            'message' => 'Invalid password'
                        ];
                    }
                }
            }
            return [
                'status' => 'error',
                'message' => 'Incorrect credentials'
            ];
        } catch (\Exception $e) {
            error_log('Error verifying login: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Login verification failed'
            ];
        }
    }

    public function getUserById($userId) {
        try {
            $reference = $this->database->getReference('users/' . $userId);
            $user = $reference->getValue();
            return $user;
        } catch (\Exception $e) {
            error_log('Error fetching user: ' . $e->getMessage());
            return null;
        }
    }

    public function getUserAvatar($userId) {
        try {
            $reference = $this->database->getReference('users/' . $userId);
            $user = $reference->getValue();
            return $user;
        } catch (\Exception $e) {
            error_log('Error fetching user: ' . $e->getMessage());
            return null;
        }
    }


    public function updateUser($userId, $userData) {
        try {
            $reference = $this->database->getReference('users/' . $userId);
            $reference->update($userData);
            return true;
        } catch (\Exception $e) {
            error_log('Error updating user: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($userId) {
        try {
            $reference = $this->database->getReference('users/' . $userId);
            $reference->remove();
            return true;
        } catch (\Exception $e) {
            error_log('Error deleting user: ' . $e->getMessage());
            return false;
        }
    }

    
/**
 * Create a new role with auto-incremented ID
 * 
 * @param string $roleName The name of the new role
 * @param bool $isAdmin Whether this is an admin role
 * @return bool True if the role was created successfully, false otherwise
 */
public function createRole($roleName, $isAdmin = false) {
    try {
        $roles = $this->getAllRoles();
        
        // Find the highest role_id
        $maxRoleId = 0;
        foreach ($roles as $role) {
            if ($role && isset($role['role_id']) && $role['role_id'] > $maxRoleId) {
                $maxRoleId = $role['role_id'];
            }
        }
        
        // Create new role with next ID
        $newRoleId = $maxRoleId + 1;
        $newRole = [
            'role_id' => $newRoleId,
            'role_name' => $roleName,
            'is_admin' => $isAdmin
        ];
        
        // Add to roles array at next position
        $roles[] = $newRole;
        
        // Update the roles in Firebase
        $this->database->getReference('roles')->set($roles);
        return true;
    } catch (Exception $e) {
        error_log('Failed to create role: ' . $e->getMessage());
        return false;
    }
}

    /**
 * Create a new role with specified ID and admin status
 * 
 * @param string $roleName The name of the new role
 * @param int $roleId The specific ID to use for this role
 * @param bool $isAdmin Whether this is an admin role
 * @return bool True if the role was created successfully, false otherwise
 */
public function createRoleWithId($roleName, $roleId, $isAdmin = false) {
    try {
        $roles = $this->getAllRoles();
        
        // Create new role with specified ID
        $newRole = [
            'role_id' => $roleId,
            'role_name' => $roleName,
            'is_admin' => $isAdmin
        ];
        
        // Update or add the role at the specified index
        $roles[$roleId] = $newRole;
        
        // Update the roles in Firebase
        $this->database->getReference('roles')->set($roles);
        return true;
    } catch (Exception $e) {
        error_log('Failed to create role with ID ' . $roleId . ': ' . $e->getMessage());
        return false;
    }
}



public function getRoleById($roleId) {
    try {
        $reference = $this->database->getReference('roles/' . $roleId);
        $role = $reference->getValue();
        return $role;
    } catch (\Exception $e) {
        error_log('Error fetching role: ' . $e->getMessage());
        return null;
    }
}

public function getAllRoles() {
    try {
        $reference = $this->database->getReference('roles');
        $roles = $reference->getValue();
        
        // Check if roles is an array and filter out any null values
        if (is_array($roles)) {
            $roles = array_filter($roles, function($role) {
                return $role !== null;
            });
        }

        return $roles;
    } catch (\Exception $e) {
        error_log('Error fetching roles: ' . $e->getMessage());
        return null;
    }
}

/**
 * Delete a role by its role_id
 * 
 * @param int $roleId The role ID to delete
 * @return bool True if deletion successful, false otherwise
 */
public function deleteRole($roleId) {
    try {
        // Get all roles first
        $roles = $this->getAllRoles();
        
        // Convert roleId to integer for comparison
        $roleId = (int)$roleId;
        
        // Find the role with matching role_id and set it to null
        $roleFound = false;
        foreach ($roles as $index => $role) {
            if ($role && isset($role['role_id']) && (int)$role['role_id'] === $roleId) {
                $roles[$index] = null; // Set to null instead of removing
                $roleFound = true;
                break;
            }
        }
        
        if (!$roleFound) {
            error_log("Role with ID $roleId not found");
            return false;
        }
        
        // Update the entire roles array
        $this->database->getReference('roles')->set($roles);
        return true;
    } catch (\Exception $e) {
        error_log('Error deleting role: ' . $e->getMessage());
        return false;
    }
}

public function updateRole($roleId, $roleData) {
    try {
        $reference = $this->database->getReference('roles/' . $roleId);
        $reference->update($roleData);
        return true;
    } catch (\Exception $e) {
        error_log('Error updating role: ' . $e->getMessage());
        return false;
    }
}

public function getNodeStructure($nodeName) {
    try {
        $reference = $this->database->getReference($nodeName);
        $data = $reference->getValue();
        
        error_log(json_encode([
            'type' => 'node_structure',
            'node' => $nodeName,
            'data' => $data
        ]));
        
        return $data;
    } catch (\Exception $e) {
        error_log('Error getting node structure: ' . $e->getMessage());
        return null;
    }
}
public function getAllNodes() {
    try {
        $reference = $this->database->getReference('/');
        $data = $reference->getValue();
        
        if (is_array($data)) {
            return array_keys($data);
        }
        
        return [];
    } catch (\Exception $e) {
        error_log('Error fetching all nodes: ' . $e->getMessage());
        return null;
    }
}

public function getAllNodesData() {
    try {
        // Reference to root
        $reference = $this->database->getReference('/');
        
        // Get all data
        $data = [
            'CurrentReadingID' => $this->database->getReference('CurrentReadingID')->getValue(),
            'DailyRecords' => $this->database->getReference('DailyRecords')->getValue(),
            'Past24Hours' => $this->database->getReference('Past24Hours')->getValue(),
            'HistoricalRecords' => $this->database->getReference('HistoricalRecords')->getValue(),
            'SensorConfig' => $this->database->getReference('SensorConfig')->getValue(),
            'WaterIndicator' => $this->database->getReference('WaterIndicator')->getValue(),
            'WaterLevel' => $this->database->getReference('WaterLevel')->getValue(),
            'SensorStatus' => $this->database->getReference('SensorStatus')->getValue(),
            'messageswitch' => $this->database->getReference('messageswitch')->getValue(),
            'roles' => $this->database->getReference('roles')->getValue(),
            'user_counter' => $this->database->getReference('user_counter')->getValue(),
            'users' => $this->database->getReference('users')->getValue()
        ];

        return [
            'status' => 'success',
            'timestamp' => date('Y-m-d H:i:s'),
            'data' => $data
        ];
    } catch (\Exception $e) {
        error_log('Error fetching all nodes data: ' . $e->getMessage());
        return [
            'status' => 'error',
            'message' => $e->getMessage(),
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
}

public function getAllUserMobileNumbers() {
    try {
        // Get a reference to the users node
        $usersRef = $this->database->getReference('users');
        
        // Retrieve all users
        $users = $usersRef->getValue();
        
        $mobileNumbers = [];
        if ($users) {
            foreach ($users as $user) {
                // If mobile number exists and is not empty
                if (isset($user['mobile_number']) && !empty($user['mobile_number'])) {
                    // Format the mobile number as needed
                    $mobileNumber = $user['mobile_number'];
                    
                    // Ensure the number starts with the country code
                    if (strpos($mobileNumber, '63') !== 0 && strpos($mobileNumber, '+63') !== 0) {
                        // If number starts with 0, replace with 63
                        if (strpos($mobileNumber, '0') === 0) {
                            $mobileNumber = '63' . substr($mobileNumber, 1);
                        } else {
                            $mobileNumber = '63' . $mobileNumber;
                        }
                    }
                    
                    // Remove + if present (as your API might require just the numbers)
                    $mobileNumber = str_replace('+', '', $mobileNumber);
                    
                    $mobileNumbers[] = $mobileNumber;
                }
            }
        }
        
        return $mobileNumbers;
    } catch (\Exception $e) {
        error_log('Error retrieving mobile numbers: ' . $e->getMessage());
        return [];
    }
}
public function updateRoles($roles) {
    try {
        $this->database->getReference('roles')->set($roles);
        return true;
    } catch (Exception $e) {
        error_log('Error updating roles: ' . $e->getMessage());
        return false;
    }
}

}