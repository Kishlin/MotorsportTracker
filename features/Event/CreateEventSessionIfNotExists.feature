Feature: It can create EventSteps

  Scenario: It saves a new event session
    Given the stepType "race" exists
    And the championship presentation "Formula One White" exists
    And the event "Dutch Grand Prix 2022" exists
    When a client creates the "race" session for the event "Dutch Grand Prix 2022" at "2022-09-04 13:00:00" with slug "dutchGrandPrix2022Race"
    Then the event session is saved

  Scenario: It cannot save two event sessions of the same type
    Given the eventSession "Dutch Grand Prix 2022 Race" exists
    When a client creates the "race" session for the event "Dutch Grand Prix 2022" at "2022-09-04 15:00:00" with slug "new slug"
    Then the event session creation for the same type is declined

  Scenario: It returns the id when creating a session with the same slug
    Given the eventSession "Dutch Grand Prix 2022 Race" exists
    When a client creates the "race" session for the event "Dutch Grand Prix 2022" at "2022-09-04 13:00:00" with slug "dutchGrandPrix2022Race"
    Then the id of the event session is returned
