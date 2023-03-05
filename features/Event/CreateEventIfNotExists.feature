Feature: It can create Events

  Scenario: It saves a new event
    Given the season "Formula One 2022" exists
    And the venue "Zandvoort" exists
    When a client creates the event "Dutch GP" of index 16 for the season "Formula One 2022" and venue "Zandvoort"
    Then the event is saved

  Scenario: It cannot create two events with the same index in a championship
    Given the event "Dutch Grand Prix 2022" exists
    When a client creates an event for the same season and index with slug "Dutch GP 2"
    Then the event creation with the same index is declined

  Scenario: It cannot create two events with the same slug in a championship
    Given the event "Dutch Grand Prix 2022" exists
    When a client creates an event for the same season and slug with index 0
    Then the event creation with the same slug is declined

  Scenario: It retrieves the id if it already exist
    Given the event "Dutch Grand Prix 2022" exists
    When a client creates the event "Dutch-gp" of index 16 for the season "Formula One 2022" and venue "Zandvoort"
    Then the id of the event is returned
