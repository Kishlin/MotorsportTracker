Feature: It can compute results for an event

  @current @backoffice
  Scenario: It can compute results for a single race event
    Given the classification "Max Verstappen At Dutch GP 2022 Race" exists
    And the team presentation "Red Bull Racing 2022" exists
    When a client computes results for the event "Dutch Grand Prix 2022"
    Then the race results for event "Dutch Grand Prix 2022" are computed
    And there is a result for "Max Verstappen" in race "1" position "1"

  @current @backoffice
  Scenario: It can compute results for an event with two races
    Given the classification "Ralph Boschung At Australian GP 2023 Formula Two Race One" exists
    And the classification "Theo Pourchaire At Australian GP 2023 Formula Two Race Two" exists
    And the team presentation "Art Grand Prix 2023" exists
    And the team presentation "Campos Racing 2023" exists
    When a client computes results for the event "Australian GP 2023 Formula Two"
    Then the race results for event "Australian GP 2023 Formula Two" are computed
    And there is a result for "Ralph Boschung" in race "1" position "1"
    And there is a result for "Theo Pourchaire" in race "2" position "1"
