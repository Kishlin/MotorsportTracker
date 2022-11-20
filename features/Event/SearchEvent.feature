Feature: It can search an event id

  Scenario: It searches an event id
    Given the event "Dutch Grand Prix 2022" exists
    When a client searches for the event for season "Formula One 2022" with keyword "dutch gp"
    Then the id of the event "Dutch Grand Prix 2022" is returned

  Scenario: It searches an event that does not exist
    Given the "event" "Dutch Grand Prix 2022" does not exist yet
    When a client searches for the event for season "Formula One 2022" with keyword "dutch gp"
    Then it does not receive any event id
