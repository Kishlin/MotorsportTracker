Feature: It can view the calendar

  @api
  Scenario: It views an empty calendar
    Given there are no events planned
    When a client views the calendar from "2022-09-01" to "2022-09-30"
    Then an empty calendar is viewed

  @api
  Scenario: It views a complex calendar
    Given the calendar event view "Emilia Romagna Grand Prix 2022 Sprint Qualifying White" exists
    And the calendar event view "Emilia Romagna Grand Prix 2022 Race White" exists
    And the calendar event view "Australian Grand Prix 2022 Race White" exists
    And the calendar event view "Americas Moto GP 2022 Race Black" exists
    When a client views the calendar from "2022-04-01" to "2022-04-30"
    Then a calendar is viewed with events
    | championship | color | icon       | name              | venue                                        | type              | dateTime            | reference                                        |
    | formula1     | #fff  | f1.png     | Australian GP     | Melbourne Grand Prix Circuit                 | race              | 2022-04-10 05:00:00 | Australian Grand Prix 2022 Race                  |
    | motogp       | #ddd  | motogp.png | Americas GP       | Circuit of the Americas                      | race              | 2022-04-10 18:00:00 | Americas Moto GP 2022 Race                       |
    | formula1     | #fff  | f1.png     | Emilia Romagna GP | Autodromo Internazionale Enzo e Dino Ferrari | sprint qualifying | 2022-04-23 14:30:00 | Emilia Romagna Grand Prix 2022 Sprint Qualifying |
    | formula1     | #fff  | f1.png     | Emilia Romagna GP | Autodromo Internazionale Enzo e Dino Ferrari | race              | 2022-04-24 13:00:00 | Emilia Romagna Grand Prix 2022 Race              |
