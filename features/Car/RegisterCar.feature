Feature: It can register Cars

  Scenario: It saves a new car
    Given the season "Formula One 2022" exists
    And the "Red Bull Racing" team exists
    When a client registers the car "1" for the team "Red Bull Racing" and season "Formula One 2022"
    Then the car is saved

  Scenario: It cannot duplicate cars
    Given the car "Red Bull Racing 2022 First Car" exists
    When a client registers a car with the same number
    Then the car registration is declined

  Scenario: It cannot register cars for a missing team
    Given the season "Formula One 2022" exists
    And the team "Red Bull Racing" does not exist yet
    When a client registers a car for a missing team
    Then the car registration is declined

  Scenario: It cannot register cars for a missing season
    Given the "Red Bull Racing" team exists
    And the season "Formula One 2022" does not exist yet
    When a client registers a car for a missing season
    Then the car registration is declined
