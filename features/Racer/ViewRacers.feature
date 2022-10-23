Feature: It can view racers

  Scenario: It can view two existing racers
    Given the racer for "Verstappen At Red Bull Racing In 2022" exists
    And the racer for "Perez At Red Bull Racing In 2022" exists
    When a client views the racers in season "Formula One 2022" on date "2022-06-01"
    Then the two racer views are returned

  Scenario: It get an empty response if there is no racers
    When a client views the racers in season "Formula One 2022" on date "2022-06-01"
    Then no racer views are returned
