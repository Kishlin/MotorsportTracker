Feature: It can create Retirements

  Scenario: It saves a new retirement
    Given the entry "Max Verstappen For Red Bull Racing At Australian GP 2022 Race" exists
    When a client creates the retirement for entry "Max Verstappen For Red Bull Racing At Australian GP 2022 Race"
    Then the retirement is saved
    And the id of the retirement of car number "33" in session "Australian Grand Prix 2022 Race"

  Scenario: It tries to create an existing retirement
    Given the retirement "Max Verstappen At Australian GP 2022 Race" exists
    When a client creates the retirement for the same entry
    Then the retirement is not duplicated
    And the id of the retirement of car number "33" in session "Australian Grand Prix 2022 Race"
