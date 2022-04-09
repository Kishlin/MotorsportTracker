Feature: It can create Championships

  Scenario: It saves a new championship
    When a client creates a new championship
    Then the championship is saved

  Scenario: It cannot duplicate championship
    Given a championship exists
    When a client creates a championship with the same name
    Then the championship creation is declined
