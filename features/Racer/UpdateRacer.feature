Feature: It can update the endDate of an existing racer

  @backoffice
  Scenario: It updates the end date of an existing racer
    Given the racer for "Latifi At Williams In 2022" exists
    When a client wants the racer for "Nicholas Latifi" in championship "Formula One" to end on "2022-09-05 00:00:00"
    Then the racer for "Latifi At Williams In 2022" now ends on "2022-09-04 23:59:59"

  @backoffice
  Scenario: It tries a new end date after the actual end date
    Given the racer for "Latifi At Williams In 2022" exists
    When a client wants the racer for "Nicholas Latifi" in championship "Formula One" to end on "2056-01-01 00:00:00"
    Then the request to update the racer is rejected

  @backoffice
  Scenario: It tries a new end date before the actual start date
    Given the racer for "Latifi At Williams In 2022" exists
    When a client wants the racer for "Nicholas Latifi" in championship "Formula One" to end on "1993-11-22 01:00:00"
    Then the request to update the racer is rejected
