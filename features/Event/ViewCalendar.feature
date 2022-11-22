Feature: It can view the calendar

  @api
  Scenario: It views an empty calendar
    Given there are no events planned
    When a client views the calendar
    Then an empty calendar is viewed

  @api
  Scenario: It views a complex calendar
    Given the eventStep "Emilia Romagna Grand Prix 2022 Sprint Qualifying" exists
    And the eventStep "Emilia Romagna Grand Prix 2022 Race" exists
    And the eventStep "Australian Grand Prix 2022 Race" exists
    And the eventStep "Americas Moto GP 2022 Race" exists
    When a client views the calendar
    Then a calendar is viewed with events
    | dateTime            | championship | venue                   | type              | event                          |
    | 2022-04-10 05:00:00 | Formula One  | Melbourne               | Race              | Australian Grand Prix 2022     |
    | 2022-04-10 18:00:00 | Moto Gp      | Circuit Of The Americas | Race              | Americas Moto GP 2022          |
    | 2022-04-23 14:30:00 | Formula One  | Emilia Romagna          | Sprint Qualifying | Emilia Romagna Grand Prix 2022 |
    | 2022-04-24 13:00:00 | Formula One  | Emilia Romagna          | Race              | Emilia Romagna Grand Prix 2022 |
