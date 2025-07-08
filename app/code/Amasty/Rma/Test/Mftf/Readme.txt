ReadMeMFTF (recommendations for running tests related to Rma extension).

    49 Rma specific tests, grouped by purpose, for greater convenience.

            This set of tests is recommended to be run at ce2.3.3 magento with:
            - Sample Data;
            - default return subjects: Return Reasons, Item Conditions, Return Resolutions. There are needed return subjects in the RMA module;
            - default RMA statuses. There are needed RMA statuses in the RMA module.

            Tests group: RMA
            Runs all tests.
                SSH command to run this group of tests:
                vendor/bin/mftf run:group RMA -r

            Tests group: RmaCreationByAdmin
                Runs tests related to creation Rma as admin.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test RmaCreationByAdmin -r

            Tests group: RmaCreationGuestCustomer
                Runs tests related to creation Rma as guest customer.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test RmaCreationGuestCustomer -r

            Tests group: RmaCreationLoggedInCustomer
                Runs tests related to creation Rma as logged in customer.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test RmaCreationLoggedInCustomer -r

            Tests group: RmaPartOfProductCreation
                Runs tests related to creation Rma for part of purchased products.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test RmaPartOfProductCreation -r

            Tests group: RmaRepeatCreation
                Runs tests related to creation Rma for the product with a created Rma.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test RmaRepeatCreation -r

            Tests group: RMACancel
                Runs tests related to cancel Rma and create a new one.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test RMACancel -r

            Tests group: RMAReturnRule
                Runs tests related to creation Rma according to set Rma rule.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test RMAReturnRule -r

            Tests group: ReturnSubjectsCreation
                Runs tests related to creation Rma with created return subjects as reason, condition, resolution.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test ReturnSubjectsCreation -r

            Tests group: ReturnReasonCreation
                Runs tests related to creation Rma with created return reason.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test ReturnReasonCreation -r

            Tests group: ReturnConditionCreation
                Runs tests related to creation Rma with created return condition.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test ReturnConditionCreation -r

            Tests group: ReturnResolutionCreation
                Runs tests related to creation Rma with created return resolution.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test ReturnResolutionCreation -r

            Tests group: PayerForShipping
                Runs tests related to creation Rma with information who pays for shipping.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test PayerForShipping -r

            Tests group: RMAItemStatus
                Runs tests related to creation Rma with further item status changing.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test RMAItemStatus -r

            Tests group: RMAItemStatus
                Runs tests related to creation Rma with further status changing.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test RMAItemStatus -r

            Tests group: TrackingInformation
                Runs tests related to creation Rma with adding and deleting tracking information.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test TrackingInformation -r

            Tests group: RmaRateComment
                Runs tests related to creation Rma with rating and commenting.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test RmaRateComment -r

            Tests group: Chat
                Runs tests related to creation Rma with chat using.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test Chat -r

            Tests group: RMAStatus
                Runs tests related to creation Rma with further Rma status changing.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test RMAStatus -r

            Tests group: RMARequestState
                Runs tests related to creation Rma with further request state changing.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test RMARequestState -r

            Tests group: AttachedFileInChat
                Runs tests related to attaching file in chat.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test AttachedFileInChat -r

            Tests group: RMAModuleConfiguration
                Runs tests related to extension configuration.
                SSH command to run this group of tests:
                vendor/bin/mftf run:test RMAModuleConfiguration -r

            Here and below:
            to run groups of tests related to Rma extension, it is necessary to add an image (.jpeg) with the name "rmaTestFile.jpeg" to the folder "magento/dev/tests/acceptance/tests/_data"