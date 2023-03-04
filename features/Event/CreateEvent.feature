Feature: It can create Events

  @backoffice
  Scenario: It saves a new event
    Given the season "Formula One 2022" exists
    And the venue "Zandvoort" exists
    When a client creates the event "Dutch GP" of index 16 for the season "Formula One 2022" and venue "circuit-zandvoort"
    Then the event is saved

  Scenario: It cannot create two events with the same index in a championship
    Given the event "Dutch Grand Prix 2022" exists
    When a client creates an event for the same season and index with label "Dutch GP 2"
    Then the event creation with the same index is declined

  Scenario: It cannot create two events with the same label in a championship
    Given the event "Dutch Grand Prix 2022" exists
    When a client creates an event for the same season and label with index 0
    Then the event creation with the same label is declined
