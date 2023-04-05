Feature: It syncs the event cache

  @backoffice
  Scenario: It syncs no events
    Given there are no events planned
    When a client syncs the events cache
    Then it successfully synced events
    And it has no cached events

  @backoffice
  Scenario: It syncs one event
    Given the event "Dutch Grand Prix 2022" exists
    When a client syncs the events cache
    Then it successfully synced events
    And it has "1" events cached
    And it has an event cached for "Formula One" "2022" "Dutch GP"

  @backoffice
  Scenario: It syncs multiple events
    Given the event "Australian Grand Prix 2022" exists
    And the event "Hungarian Grand Prix 2019" exists
    And the event "Americas Moto GP 2022" exists
    When a client syncs the events cache
    Then it successfully synced events
    And it has "3" events cached
    And it has an event cached for "Formula One" "2022" "Australian GP"
    And it has an event cached for "Formula One" "2019" "Hungarian GP"
    And it has an event cached for "Moto GP" "2022" "Americas GP"
