import React, {useMemo} from 'react';
import {useHistory, useLocation} from 'react-router-dom';
import styled from 'styled-components';
import {Box, Button, Flex, FlexItem, H1, Text} from '@bigcommerce/big-design';
import {ArrowBackIcon} from '@bigcommerce/big-design-icons';
import Link from './Link';
import {Theme} from '../theme';
import {Location, PageHeaderActions} from '../types';

const HeaderLink = styled(Link)`
  color: ${({theme}) => theme.colors.secondary60};

  :hover,
  :active,
  :hover:not(:active) {
    color: ${({theme}) => theme.colors.secondary70};
  }
`;

HeaderLink.defaultProps = {theme: Theme};

interface Props {
  title?: string;
  actions?: PageHeaderActions[];
  backLinkHref?: string;
  backLinkText?: string;
  storeHash?: string | null;
  children?: React.ReactNode;
}

const PageHeader = ({title, actions, backLinkHref, backLinkText, storeHash = null, children}: Props) => {
  const location = useLocation() as unknown as Location;
  const history = useHistory();

  const getBackLocation = () : string | null => {
    if (location?.state?.referrer) return location.state.referrer;
    if (backLinkHref) return backLinkHref;
    if (storeHash) return `/stores/${storeHash}/`;

    return null;
  }

  const backLocation = getBackLocation();
  const goBack = () => backLocation ? history.push(backLocation) : history.goBack();
  const homeUrl = storeHash ? `/stores/${storeHash}` : '/stores';

  const renderedTitle = useMemo(
    () => {
      return (
        <>
          {
            title &&
            <Flex alignItems="baseline">
              <FlexItem>
                <H1 marginVertical="small">{title}</H1>
              </FlexItem>
              {
                actions &&
                <>
                  {
                    actions.map(({text, variant = 'subtle', ...actionProps}, index) => (
                      <Button
                        {...actionProps}
                        key={index}
                        marginBottom="xSmall"
                        marginHorizontal="small"
                        mobileWidth="auto"
                        variant={variant}
                      >
                        {text}
                      </Button>
                    ))
                  }
                </>
              }
            </Flex>
          }
        </>
      )
    }, [title, actions]);

  return (
    <Box marginTop="medium" marginHorizontal="xxxLarge" marginBottom="xxLarge">
      <Flex justifyContent="flex-end" flexDirection="row">
        {
          storeHash &&
          <>
            <FlexItem marginHorizontal="small">
              <HeaderLink to={homeUrl}>Home</HeaderLink>
            </FlexItem>
          </>
        }
        &nbsp;
      </Flex>
      {
        backLinkText &&
        <Button
          marginTop="medium"
          mobileWidth="auto"
          iconLeft={<ArrowBackIcon color="secondary50"/>}
          variant="subtle"
          onClick={goBack}>
          <Text color="secondary50">{backLinkText}</Text>
        </Button>
      }
      {renderedTitle}
      {children}
    </Box>
  )
};

export default PageHeader;
