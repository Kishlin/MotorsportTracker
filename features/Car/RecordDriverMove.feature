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
