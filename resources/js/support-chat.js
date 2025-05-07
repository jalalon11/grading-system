/**
 * Support Chat JavaScript
 *
 * This script handles real-time updates for the support chat functionality.
 */

// Initialize support chat functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeSupportChat();
});

/**
 * Initialize the support chat functionality
 */
function initializeSupportChat() {
    const messagesContainer = document.querySelector('.messages-container');
    const ticketIdElement = document.getElementById('ticket-id');
    const currentUserIdElement = document.getElementById('current-user-id');

    // If we're not on a support chat page, return
    if (!messagesContainer || !ticketIdElement || !currentUserIdElement) {
        return;
    }

    const ticketId = ticketIdElement.value;
    const currentUserId = currentUserIdElement.value;

    // Handle hash in URL for direct scrolling to messages container
    handleHashScroll();

    // Set up scroll-to-bottom button
    setupScrollToBottomButton(messagesContainer);

    // Set up periodic checking for message read status
    // This will update the message status indicators based on server data
    setInterval(() => checkMessageReadStatus(currentUserId), 10000); // Check every 10 seconds

    // Check if we just sent a message (page reloaded after form submission)
    const messageSent = window.location.search.includes('success=true') || localStorage.getItem('messageSent') === 'true';

    if (messageSent) {
        console.log('Message was just sent, updating status indicators');

        // Clear the flag if it exists
        localStorage.removeItem('messageSent');

        // Remove the success parameter from URL if present
        if (window.location.search.includes('success=true')) {
            const url = new URL(window.location);
            url.searchParams.delete('success');
            window.history.replaceState({}, '', url);
        }

        // Find the last message from the current user
        const allMessages = document.querySelectorAll('.message-wrapper[data-message-id]');
        let lastUserMessage = null;

        // Find the last message from the current user
        for (let i = allMessages.length - 1; i >= 0; i--) {
            const message = allMessages[i];
            const isCurrentUserMessage = message.classList.contains('teacher-admin-message') ||
                                        message.classList.contains('admin-message');

            if (isCurrentUserMessage) {
                lastUserMessage = message;
                break;
            }
        }

        if (lastUserMessage) {
            console.log('Found last user message:', lastUserMessage);

            // Update the message status to "sent" only
            setTimeout(() => {
                // Only show "sent" status initially
                updateMessageStatus(lastUserMessage, 'sent');

                // After a short delay, show "delivered" but don't progress to "read"
                // This simulates the message being delivered to the server
                setTimeout(() => {
                    updateMessageStatus(lastUserMessage, 'delivered');
                    // We don't automatically progress to "read" anymore
                    // The "read" status will only be set when the admin actually reads the message
                }, 1500);
            }, 100);
        } else {
            console.error('Could not find the last user message');
        }

        // Scroll to bottom
        scrollToBottom(messagesContainer);
    } else {
        // Check if we have a saved scroll position
        const savedScrollPosition = localStorage.getItem('chatScrollPosition');
        if (savedScrollPosition) {
            console.log('Restoring saved scroll position:', savedScrollPosition);
            // Restore the scroll position
            messagesContainer.scrollTop = parseInt(savedScrollPosition, 10);
            // Clear the saved position
            localStorage.removeItem('chatScrollPosition');
        } else {
            // Default: scroll to bottom
            scrollToBottom(messagesContainer);
        }
    }

    // Listen for new messages
    listenForNewMessages(ticketId, currentUserId, messagesContainer);

    // Set up form submission
    setupFormSubmission();

    // Set up auto-refresh as fallback
    setupAutoRefresh(ticketId);
}

/**
 * Listen for new messages using page refresh
 */
function listenForNewMessages(ticketId, currentUserId, messagesContainer) {
    // Set up auto-refresh for all browsers
    console.log('Setting up auto-refresh for messages');

    // Determine if we're in admin or teacher-admin context based on URL
    const path = window.location.pathname;
    console.log(`Current path: ${path}`);

    // Check if we're in the admin section
    const isAdmin = path.includes('/admin/');
    console.log(`Is admin: ${isAdmin}`);

    // Define all possible API URL formats to try
    const apiUrls = [
        // New format URLs
        isAdmin
            ? `/admin/api/support/tickets/${ticketId}/messages`
            : `/teacher-admin/api/support/tickets/${ticketId}/messages`,
        // Original format URL as fallback
        `/api/support/tickets/${ticketId}/messages`
    ];

    // Start with the first URL format
    let currentApiUrlIndex = 0;
    let apiUrl = apiUrls[currentApiUrlIndex];

    console.log(`Using API URL (initial): ${apiUrl}`);

    // Track the latest message ID we've seen
    let lastMessageId = 0;

    // Find the latest message ID from the DOM if possible
    const messageElements = document.querySelectorAll('.message-wrapper');
    if (messageElements.length > 0) {
        // Try to extract message ID from data attribute if available
        const lastMessageElement = messageElements[messageElements.length - 1];
        if (lastMessageElement.dataset.messageId) {
            lastMessageId = parseInt(lastMessageElement.dataset.messageId, 10);
        }
    }

    console.log(`Starting with last message ID: ${lastMessageId}`);

    setInterval(() => {
        // Get the current API URL to try
        apiUrl = apiUrls[currentApiUrlIndex];
        console.log(`Trying API URL: ${apiUrl}`);

        // Add last_id parameter to the URL
        const urlWithParams = new URL(apiUrl, window.location.origin);
        if (lastMessageId > 0) {
            urlWithParams.searchParams.append('last_id', lastMessageId);
        }

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log(`CSRF Token: ${csrfToken ? 'Found' : 'Not found'}`);

        // Make the fetch request with proper headers
        fetch(urlWithParams, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json'
            }
        })
            .then(response => {
                console.log(`Response status: ${response.status}`);
                if (!response.ok) {
                    throw new Error(`Network response was not ok: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Message check response:', data);

                // Update our tracking of the latest message ID
                if (data.latestMessageId && data.latestMessageId > lastMessageId) {
                    lastMessageId = data.latestMessageId;
                    console.log(`Updated last message ID to: ${lastMessageId}`);
                }

                // Reload the page to show new messages
                if (data.hasNewMessages) {
                    console.log('New messages detected, reloading page');
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error checking for new messages:', error);

                // Try the next URL format if available
                currentApiUrlIndex = (currentApiUrlIndex + 1) % apiUrls.length;
                apiUrl = apiUrls[currentApiUrlIndex];
                console.log(`Switching to next API URL format: ${apiUrl}`);
            });
    }, 5000); // Check every 5 seconds
}

/**
 * Add a new message to the chat
 */
function addMessageToChat(message, currentUserId, messagesContainer) {
    const isCurrentUser = message.user.id == currentUserId;

    const messageItem = document.createElement('div');
    messageItem.className = `message-item mb-3 ${isCurrentUser ? 'text-end' : ''}`;

    const messageBubble = document.createElement('div');
    messageBubble.className = `message-bubble d-inline-block p-3 rounded ${isCurrentUser ? 'bg-primary text-white' : 'bg-white border'}`;
    messageBubble.style.maxWidth = '80%';

    const messageContent = document.createElement('div');
    messageContent.className = 'message-content';
    messageContent.textContent = message.message;

    const messageMeta = document.createElement('div');
    messageMeta.className = `message-meta mt-2 ${isCurrentUser ? 'text-white-50' : 'text-muted'} small`;
    messageMeta.innerHTML = `<span>${message.user.name}</span> • <span>${message.created_at}</span>`;

    messageBubble.appendChild(messageContent);
    messageBubble.appendChild(messageMeta);
    messageItem.appendChild(messageBubble);

    messagesContainer.appendChild(messageItem);

    // Scroll to the bottom
    scrollToBottom(messagesContainer);
}

/**
 * Mark a message as read
 */
function markMessageAsRead(messageId) {
    // Determine if we're in admin or teacher-admin context based on URL
    const isAdmin = window.location.pathname.includes('/admin/');
    const apiUrl = isAdmin
        ? `/admin/api/support/messages/${messageId}/read`
        : `/teacher-admin/api/support/messages/${messageId}/read`;

    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });
}

/**
 * Set up form submission with AJAX
 */
function setupFormSubmission() {
    const form = document.querySelector('.reply-form form');
    const messagesContainer = document.querySelector('.messages-container');

    if (!form) {
        return;
    }

    form.addEventListener('submit', function(e) {
        // Don't prevent default - we want the form to submit normally
        // But we'll save the scroll position before it submits

        const messageInput = form.querySelector('textarea[name="message"]');
        const message = messageInput.value.trim();

        if (!message) {
            e.preventDefault(); // Prevent submission if message is empty
            return;
        }

        // Save the scroll position and a flag indicating we just sent a message
        localStorage.setItem('chatScrollPosition', messagesContainer.scrollHeight);
        localStorage.setItem('messageSent', 'true');

        // Force the URL to include success=true parameter and anchor when the form is submitted
        // This will ensure the success message appears after reload and the page scrolls to the messages
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('success', 'true');

        // Add the anchor to the URL to scroll to the messages container
        // We'll use the hash fragment to target the messages container
        currentUrl.hash = 'messages-container';

        // Add a hidden input to the form to redirect back to the URL with success parameter and anchor
        const redirectInput = document.createElement('input');
        redirectInput.type = 'hidden';
        redirectInput.name = 'redirect_url';
        redirectInput.value = currentUrl.toString();
        form.appendChild(redirectInput);

        // Let the form submit normally - the page will reload
        console.log('Form submitting, saved scroll position:', messagesContainer.scrollHeight);
    });
}

/**
 * Set up auto-refresh for form submission
 */
function setupAutoRefresh(ticketId) {
    // We don't need this anymore since we're letting the form submit normally
    console.log('Auto-refresh after form submission is handled by normal form submission');
}

/**
 * Update the message status indicator
 */
function updateMessageStatus(messageElement, status) {
    if (!messageElement) {
        console.error('Message element is null or undefined');
        return;
    }

    // Find the status indicators container
    const statusContainer = messageElement.querySelector('.message-status-indicators');
    if (!statusContainer) {
        console.error('Status container not found in message element');
        return;
    }

    console.log(`Updating message status to: ${status} for message:`, messageElement);

    // Hide all status indicators first
    const allStatusIndicators = statusContainer.querySelectorAll('.message-status');
    allStatusIndicators.forEach(indicator => {
        indicator.classList.add('d-none');
        indicator.classList.remove('active');
    });

    // Show only the current status indicator
    const currentStatus = statusContainer.querySelector(`.message-status[data-status="${status}"]`);
    if (currentStatus) {
        // Make sure all others are hidden
        allStatusIndicators.forEach(indicator => {
            if (indicator !== currentStatus) {
                indicator.classList.add('d-none');
            }
        });

        // Show the current one
        currentStatus.classList.remove('d-none');
        currentStatus.classList.add('active');

        // Add color to read status
        if (status === 'read') {
            const icon = currentStatus.querySelector('i');
            if (icon) {
                icon.classList.add('text-primary');
            }
        }

        console.log(`Successfully updated message status to: ${status}`);
    } else {
        console.error(`Status indicator for '${status}' not found`);
    }
}

/**
 * Check for message read status from the server
 * This function will update the message status indicators based on the actual read status
 */
function checkMessageReadStatus(currentUserId) {
    // Only run this for teacher-admin users (they're the ones who need to see read status)
    if (!window.location.pathname.includes('/teacher-admin/')) {
        return;
    }

    console.log('Checking message read status from server...');

    // Get all messages from the current user
    const userMessages = document.querySelectorAll('.message-wrapper.teacher-admin-message');
    if (!userMessages.length) {
        return;
    }

    // Create a map of message elements by ID for quick lookup
    const messageElementsById = {};
    userMessages.forEach(message => {
        const messageId = message.getAttribute('data-message-id');
        if (messageId) {
            messageElementsById[messageId] = message;
        }
    });

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Fetch the read status from the server
    fetch('/teacher-admin/api/support/messages/read-status', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken || '',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Network response was not ok: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Message read status response:', data);

        if (data.success && data.messages) {
            // Update the status for each message
            Object.values(data.messages).forEach(messageData => {
                const messageElement = messageElementsById[messageData.id];
                if (messageElement) {
                    // Update the data-is-read attribute
                    messageElement.setAttribute('data-is-read', messageData.is_read ? 'true' : 'false');

                    // Update the status indicator
                    if (messageData.is_read) {
                        updateMessageStatus(messageElement, 'read');
                    } else {
                        // If not read, keep at delivered status
                        const currentStatus = messageElement.querySelector('.message-status:not(.d-none)');
                        if (currentStatus) {
                            const status = currentStatus.getAttribute('data-status');
                            // Only change if it's showing "read" but should be "delivered"
                            if (status === 'read') {
                                updateMessageStatus(messageElement, 'delivered');
                            }
                        }
                    }
                }
            });
        }
    })
    .catch(error => {
        console.error('Error checking message read status:', error);
    });
}

/**
 * Set up the scroll-to-bottom button functionality
 */
function setupScrollToBottomButton(messagesContainer) {
    const scrollToBottomBtn = document.getElementById('scroll-to-bottom');
    if (!scrollToBottomBtn || !messagesContainer) return;

    // Show/hide the button based on scroll position
    messagesContainer.addEventListener('scroll', function() {
        // If we're not near the bottom, show the button
        const isNearBottom = messagesContainer.scrollHeight - messagesContainer.scrollTop - messagesContainer.clientHeight < 100;

        if (isNearBottom) {
            scrollToBottomBtn.classList.add('d-none');
        } else {
            scrollToBottomBtn.classList.remove('d-none');
        }
    });

    // Scroll to bottom when the button is clicked
    scrollToBottomBtn.addEventListener('click', function() {
        scrollToBottom(messagesContainer);
    });
}

/**
 * Handle hash in URL for direct scrolling to messages container
 */
function handleHashScroll() {
    // If we have a hash in the URL, scroll the element into view
    if (window.location.hash === '#messages-container') {
        const messagesContainer = document.getElementById('messages-container');
        if (messagesContainer) {
            // First scroll the element into view
            messagesContainer.scrollIntoView({ behavior: 'smooth' });

            // Then scroll to the bottom of the container
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            console.log('Scrolled to messages container via hash');

            // Focus the message input if it exists
            const messageInput = document.getElementById('message');
            if (messageInput) {
                setTimeout(() => {
                    messageInput.focus();
                }, 600);
            }
        }
    }
}

/**
 * Scroll to the bottom of the container
 */
function scrollToBottom(container) {
    if (container) {
        // First immediate scroll attempt
        container.scrollTop = container.scrollHeight;
        console.log('Initial scroll to bottom, height:', container.scrollHeight);

        // Then use multiple timeouts to ensure scrolling happens after all DOM updates
        // This helps with various browser rendering timings
        setTimeout(() => {
            container.scrollTop = container.scrollHeight;
            console.log('Scroll attempt 1, height:', container.scrollHeight);

            // Second attempt after a bit longer
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
                console.log('Scroll attempt 2, height:', container.scrollHeight);

                // Final attempt after all rendering should be complete
                setTimeout(() => {
                    container.scrollTop = container.scrollHeight;
                    console.log('Final scroll attempt, height:', container.scrollHeight);

                    // If we have a hash in the URL, scroll the element into view
                    if (window.location.hash === '#messages-container') {
                        const messagesContainer = document.getElementById('messages-container');
                        if (messagesContainer) {
                            messagesContainer.scrollIntoView({ behavior: 'smooth' });
                            // Also scroll to bottom of the container
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        }
                    }
                }, 500);
            }, 200);
        }, 100);
    }
}
