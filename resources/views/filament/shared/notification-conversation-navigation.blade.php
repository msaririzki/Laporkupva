<script data-notification-conversation-navigation>
    (() => {
        if (window.tamboraNotificationConversationNavigationInitialized) {
            return
        }

        window.tamboraNotificationConversationNavigationInitialized = true

        const conversationHash = '#komunikasi-anonim'
        const databaseNotificationsModalId = 'database-notifications'

        const focusConversationReply = () => {
            if (window.location.hash !== conversationHash) {
                return
            }

            const conversation = document.querySelector(conversationHash)
            const replyField = conversation?.querySelector('#admin-report-reply')
            const focusTarget = replyField ?? conversation

            if (! focusTarget) {
                return
            }

            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches

            focusTarget.scrollIntoView({
                behavior: prefersReducedMotion ? 'auto' : 'smooth',
                block: 'center',
            })

            window.setTimeout(() => {
                replyField?.focus({ preventScroll: true })
            }, prefersReducedMotion ? 0 : 350)
        }

        document.addEventListener('click', (event) => {
            const conversationLink = event.target.closest(
                '.fi-no-database a[href*="#komunikasi-anonim"]',
            )

            if (! conversationLink) {
                return
            }

            window.dispatchEvent(new CustomEvent('close-modal', {
                detail: { id: databaseNotificationsModalId },
            }))

            window.setTimeout(focusConversationReply, 350)
        }, true)

        document.addEventListener('livewire:navigated', focusConversationReply)
        window.addEventListener('hashchange', focusConversationReply)
        window.requestAnimationFrame(focusConversationReply)
    })()
</script>
