<?php

// Create a new Redis instance
$redis = new Redis();

try {
    // Connect to Redis server
    $redis->connect('redis-10197.c8.us-east-1-2.ec2.redns.redis-cloud.com', 10197);

    // Authenticate using password
    $redis->auth('1yqW3a6khbglWHydI3MzQ1Uq9h89OAfn');  // Replace with your actual Redis password

    // Check if the connection is successful
    if ($redis->ping()) {
        echo "Connected to Redis!";
    }

    // You can now use Redis commands, e.g., set a key
    $redis->set('test_key', 'Hello, Redis!');

    // Get the value of the key
    echo $redis->get('test_key');
} catch (RedisException $e) {
    echo "Redis connection failed: " . $e->getMessage();
}
