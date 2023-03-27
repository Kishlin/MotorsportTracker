Feature: It synchronizes calendar events from core data

  @backoffice
  Scenario: It computes calendar events for planned events
    Given the championship presentation "Formula One White" exists
    And the eventSession "Dutch Grand Prix 2022 Race" exists
    When a client synchronizes calendar events for "Formula One" "2022"
    Then it cached "1" calendar events
    And there is a calendar event cached for "Dutch Grand Prix 2022"

  @backoffice
  Scenario: It merges event sessions into
    Given the championship presentation "Formula One White" exists
    And the eventSession "Emilia Romagna Grand Prix 2022 Race" exists
    And the eventSession "Emilia Romagna Grand Prix 2022 Sprint Qualifying" exists
    When a client synchronizes calendar events for "Formula One" "2022"
    Then it cached "1" calendar events
    And there is a calendar event cached for "Emilia Romagna Grand Prix 2022"

  @backoffice
  Scenario: It targets a specific championship and year
    Given the championship presentation "Formula One White" exists
    And the eventSession "Emilia Romagna Grand Prix 2022 Sprint Qualifying" exists
    And the eventSession "Emilia Romagna Grand Prix 2022 Race" exists
    And the eventSession "Dutch Grand Prix 2022 Race" exists
    And the eventSession "Americas Moto GP 2022 Race" exists
    And the eventSession "Hungarian Grand Prix 2019 Race" exists
    When a client synchronizes calendar events for "Formula One" "2022"
    Then it cached "2" calendar events
    And there is a calendar event cached for "Dutch Grand Prix 2022"
    And there is a calendar event cached for "Emilia Romagna Grand Prix 2022"

  @backoffice
  Scenario: It does nothing when there are no events to synchronize
    Given there are no events planned
    When a client synchronizes calendar events for "Formula One" "2022"
    Then it cached "0" calendar events
