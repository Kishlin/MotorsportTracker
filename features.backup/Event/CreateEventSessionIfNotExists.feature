Feature: It can create EventSteps

  Scenario: It saves a new event session
    Given the sessionType "race" exists
    And the championship presentation "Formula One White" exists
    And the event "Dutch Grand Prix 2022" exists
    When a client creates the "race" session for the event "Dutch Grand Prix 2022" at "2022-09-04 13:00:00" with slug "dutchGrandPrix2022Race"
    Then the event session is saved

  Scenario: It returns the id when creating a session with the same slug
    Given the eventSession "Dutch Grand Prix 2022 Race" exists
    When a client creates the "race" session for the event "Dutch Grand Prix 2022" at "2022-09-04 13:00:00" with slug "dutchGrandPrix2022Race"
    Then the id of the event session is returned
