Feature: It can create Seasons

  @backoffice
  Scenario: It saves a new season
    Given the championship "Formula One" exists
    When a client creates the season "2022" for the championship "Formula One"
    Then the season is saved

  @backoffice
  Scenario: It cannot duplicate seasons
    Given the season "Formula One 2022" exists
    When a client creates a season for the same championship and year
    Then the season creation is declined
