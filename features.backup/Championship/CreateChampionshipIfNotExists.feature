Feature: It can create Championships

  Scenario: It saves a new championship
    When a client creates the championship "Formula One" with code "f1"
    Then the championship is saved

  Scenario: It cannot duplicate championship
    Given the championship "Formula One" exists
    When a client creates a championship with the same name
    Then the championship id is returned
