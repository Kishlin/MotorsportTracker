Feature: It can search an event step's id and date time

  Scenario: It searches an event step's id and date time
    Given the eventStep "Dutch Grand Prix 2022 Race" exists
    When a client searches for the "race" step for season "Formula One 2022" with keyword "dutch gp"
    Then the id and date time of the event step "Dutch Grand Prix 2022 Race" are returned

  Scenario: It searches an event whose type does not exist
    Given the eventStep "Dutch Grand Prix 2022 Race" exists
    When a client searches for the "sprint" step for season "Formula One 2022" with keyword "dutch gp"
    Then it does not receive any event step info

  Scenario: It searches an event that does not exist
    Given the "event step" "Dutch Grand Prix 2022 Race" does not exist yet
    When a client searches for the "race" step for season "Formula One 2022" with keyword "dutch gp"
    Then it does not receive any event step info
