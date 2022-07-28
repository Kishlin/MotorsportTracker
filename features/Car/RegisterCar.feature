Feature: It can register Cars

  Scenario: It saves a new car
    Given a championship exists
    And a season exists for the championship
    And a country exists
    And a team exists for the country
    When a client registers a car for the team and season
    Then the car is saved

  Scenario: It cannot duplicate cars
    Given a championship exists
    And a season exists for the championship
    And a country exists
    And a team exists for the country
    And a car exists for the team and season
    When a client registers a car with the same number
    Then the car registration is declined

  Scenario: It cannot register cars for a missing team
    Given a championship exists
    And a season exists for the championship
    When a client registers a car for a missing team
    Then the car registration is declined

  Scenario: It cannot register cars for a missing season
    Given a country exists
    And a team exists for the country
    When a client registers a car for a missing season
    Then the car registration is declined
