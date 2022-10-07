Feature: It can view racers

  Scenario: It can view two existing racers
    Given a championship exists
    And a season exists for the championship
    And a country exists
    And a team exists for the country
    And a driver exists for the country
    And a car exists for the team and season
    And a racer exists for the driver and car
    And another country exists
    And another driver exists for the other country
    And another car exists for another driver
    And another racer exists for the other driver and other car
    When a client views the racers during the championship
    Then the two racer views is are returned

  Scenario: It get an empty response if there is no racers
    When a client views the racers during the championship
    Then no racer views are returned
