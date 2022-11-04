Feature: It can create Championships

  @backoffice
  Scenario: It saves a new championship
    When a client creates the championship "Formula 1" with slug "f1"
    Then the championship is saved

  @backoffice
  Scenario: It cannot duplicate championship
    Given the championship "Formula One" exists
    When a client creates a championship with the same name
    Then the championship creation is declined
