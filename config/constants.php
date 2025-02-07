<?php

// NUMBER OF SHIPS THAT CAN BUILD FOR ONCE
define('MAX_FLEET_OR_DEFS_PER_ROW', 9999);

// MAX RESULTS TO SHOW IN SEARCH
define('MAX_SEARCH_RESULTS', 25);

//PLANET SIZE MULTIPLER
define('PLANETSIZE_MULTIPLER', 1);

// INITIAL RESOURCE OF NEW PLANETS
define('BUILD_METAL', 500);
define('BUILD_CRISTAL', 500);
define('BUILD_DEUTERIUM', 0);

// OFFICIERS DEFAULT VALUES
define('AMIRAL', 2);
define('ENGINEER_DEFENSE', 2);
define('ENGINEER_ENERGY', 0.1);
define('GEOLOGUE', 0.1);
define('TECHNOCRATE_SPY', 2);
define('TECHNOCRATE_SPEED', 0.25);

// INVISIBLES DEBRIS
define('DEBRIS_LIFE_TIME', ONE_WEEK);
define('DEBRIS_MIN_VISIBLE_SIZE', 300);

// DESTROYED PLANETS LIFE TIME
define('PLANETS_LIFE_TIME', 24); // IN HOURS

// VACATION TIME THAT AN USER HAS TO BE ON VACATION MODE BEFORE IT CAN REMOVE IT
define('VACATION_TIME_FORCED', 2); // IN DAYS

// RESOURCE MARKET
define('BASIC_RESOURCE_MARKET_DM', [
    'metal' => 4500,
    'crystal' => 9000,
    'deuterium' => 13500,
]);

// PHALANX COST
define('PHALANX_COST', 10000);
