Feature: It can create Seasons

  Scenario: It saves a new season
    Given a championship exists
    When a client creates a season for the championship
    Then the season is saved

  Scenario: It cannot duplicate seasons
    Given a championship exists
    And a season exists for the championship
    When a client creates a season for the same year
    Then the season creation is declined
