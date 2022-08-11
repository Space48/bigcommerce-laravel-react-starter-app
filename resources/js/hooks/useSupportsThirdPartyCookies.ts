/**
 * Loading BigCommerce app in admin requires third party cookies to be supported.
 * Safari & Private browsing modes do not support third party cookies.
 * Detect if third party cookies are supported by the browser.
 */
import {useCookies} from 'react-cookie';
import {useEffect, useState} from 'react';

export const useSupportsThirdPartyCookies = () => {
  // Assume supported unless otherwise detected
  const [isThirdPartyCookiesSupported, setIsThirdPartyCookiesSupported] = useState(true);
  const [testCookieWritten, setTestCookieWritten] = useState(false);
  const testCookieName = 's48_app_test_3rd_party';

  const [cookies, setCookie, removeCookie] = useCookies([testCookieName]);

  useEffect(() => {
    if (!testCookieWritten) {
      setCookie(testCookieName, 1, {path: '/', sameSite: 'none', secure: true});
      setTestCookieWritten(true);
    } else {
      if (!cookies[testCookieName]) {
        setIsThirdPartyCookiesSupported(false);
      }
      removeCookie(testCookieName);
    }
  }, [testCookieWritten]);

  return isThirdPartyCookiesSupported;
}
