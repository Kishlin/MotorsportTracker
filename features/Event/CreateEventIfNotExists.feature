Feature: It can create Events

  Scenario: It saves a new event
    Given the season "Formula One 2022" exists
    And the venue "Zandvoort" exists
    When a client creates the event "Dutch GP" of index 16 for the season "Formula One 2022" and venue "Zandvoort"
    Then the event is saved
    And the id of the new event is returned

  Scenario: It cannot create two events with the same index in a championship
    Given the event "Dutch Grand Prix 2022" exists
    When a client creates an event for the same season and index with name "Dutch GP 2"
    Then it does not recreate the existing event
    And the id of the existing event is returned

  Scenario: It cannot create two events with the same name in a championship
    Given the event "Dutch Grand Prix 2022" exists
    When a client creates an event for the same season and name with index 0
    Then it does not recreate the existing event
    And the id of the existing event is returned
