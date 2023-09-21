Feature: It can create missing StepTypes

  Scenario: It saves a new session type
    Given the sessionType "race" does not exist yet
    When a client searches for the sessionType with label "race"
    Then the session type is saved

  Scenario: It does not recreate an existing session type
    Given the sessionType "race" exists
    When a client searches for the sessionType with label "race"
    Then the session type is not recreated
