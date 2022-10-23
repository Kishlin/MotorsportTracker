Feature: It can create missing StepTypes

  Scenario: It saves a new step type
    Given the stepType "race" does not exist yet
    When a client searches for the stepType with label "race"
    Then the step type is saved

  Scenario: It does not recreate an existing step type
    Given the stepType "race" exists
    When a client searches for the stepType with label "race"
    Then the step type is not recreated
