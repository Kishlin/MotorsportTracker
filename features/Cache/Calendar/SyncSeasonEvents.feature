Feature: It syncs season events

  @backoffice
  Scenario: It syncs existing events
    Given the event "Australian Grand Prix 2022" exists
    And the event "Dutch Grand Prix 2022" exists
    When a client syncs the season events for "Formula One" "2022"
    Then the season events are cached for "Formula One" "2022"
    And it cached the event of slug "australian-gp" for "Formula One" "2022"
    And it cached the event of slug "dutch-gp" for "Formula One" "2022"

  @backoffice
  Scenario: It skips events of other championship and seasons
    Given the event "Australian Grand Prix 2022" exists
    And the event "Hungarian Grand Prix 2019" exists
    And the event "Americas Moto GP 2022" exists
    When a client syncs the season events for "Formula One" "2022"
    Then the season events are cached for "Formula One" "2022"
    And it cached the event of slug "australian-gp" for "Formula One" "2022"
    And it did not cache the event of slug "hungarian-gp" for "Formula One" "2022"
    And it did not cache the event of slug "americas-gp" for "Formula One" "2022"

  @backoffice
  Scenario: It syncs a season with no events
    Given the season "Formula One 2022" exists
    When a client syncs the season events for "Formula One" "2022"
    Then the season events are cached for "Formula One" "2022"
    And it cached no events for "Formula One" "2022"
