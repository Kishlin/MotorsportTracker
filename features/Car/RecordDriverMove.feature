Feature: It can record Driver Moves

  Scenario: It saves a new driver move
    Given a championship exists
    And a season exists for the championship
    And a country exists
    And a team exists for the country
    And a driver exists for the country
    And a car exists for the team and season
    When a client records a driver move for the driver and car
    Then the driver move is recorded
    And the racer data is created

  Scenario: It handles a mid-season driver move
    Given a championship exists
    And a season exists for the championship
    And a country exists
    And a team exists for the country
    And a driver exists for the country
    And a car exists for the team and season
    And the driver moved to the car for season starts
    And a racer exists for the driver and car
    And another team exists for the country
    And another car exists for the other team and season
    When a client records a driver move for the driver and car
    And a client records a driver move for the driver and the other car
    Then the second driver move is recorded
    And the racer data is split at the time of the driver move

  Scenario: It cannot duplicate moves for a driver
    Given a championship exists
    And a season exists for the championship
    And a country exists
    And a team exists for the country
    And a driver exists for the country
    And a car exists for the team and season
    And a driver move exists for the driver and date
    When a client records a driver move with the same driver and date
    Then the driver move recording is declined

  Scenario: It cannot duplicate moves for a car
    Given a championship exists
    And a season exists for the championship
    And a country exists
    And a team exists for the country
    And a driver exists for the country
    And a car exists for the team and season
    And a driver move exists for the car and date
    When a client records a driver move with the same car and date
    Then the driver move recording is declined

  Scenario: It cannot record driver moves for a missing car
    Given a championship exists
    And a season exists for the championship
    And a country exists
    And a driver exists for the country
    When a client records a driver move for a missing car
    Then the driver move recording is declined

  Scenario: It cannot record driver moves for a missing driver
    Given a championship exists
    And a season exists for the championship
    And a country exists
    And a team exists for the country
    And a car exists for the team and season
    When a client records a driver move for a missing driver
    Then the driver move recording is declined
