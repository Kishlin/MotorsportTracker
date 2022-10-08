Feature: It can create missing StepTypes

  Scenario: It saves a new step type
    When a client searches a step type which does not exist
    Then the new step type is saved

  Scenario: It does not recreate an existing step type
    Given a step type exists
    When a client searches for the existing step type
    Then the step type was not recreated
