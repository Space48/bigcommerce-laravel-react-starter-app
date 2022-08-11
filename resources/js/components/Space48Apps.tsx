import {Button, Flex, FlexItem, Grid, GridItem, H3, Link, Panel, Small, Text} from "@bigcommerce/big-design";
import React from "react";
import styled from "styled-components";
import {Theme} from "../theme";

const AppsGrid = styled(Grid)`
  grid-template-columns: 1fr;
  margin: ${({theme}) => theme.spacing.xxLarge} auto;
  max-width: 52em;
  text-align: center;


  ${({theme}) => theme.breakpoints.tablet} {
    grid-template-columns: repeat(2, 1fr);
    text-align: left;
  }
`;

AppsGrid.defaultProps = {theme: Theme};

const AppsGridItem = styled(GridItem)`
  margin: ${({theme}) => theme.spacing.medium} auto;
  max-width: 16em;

  ${({theme}) => theme.breakpoints.tablet} {
    max-width: 24em;
  }
`;
AppsGridItem.defaultProps = {theme: Theme}

interface app {
  name: string,
  description: string,
  icon_url: string,
  appstore_url: string,
}

const appsConfig: app[] = [
  {
    name: 'Automated Categories',
    description: 'Automate assigning products to categories using conditions.',
    icon_url: '/img/icons/automated-categories.png',
    appstore_url: 'https://www.bigcommerce.com/apps/automated-categories/'
  },
  {
    name: 'Category Merchandiser',
    icon_url: '/img/icons/category-merchandiser.png',
    description: 'Improve product discovery with a visual merchandiser and automated ranking rules.',
    appstore_url: 'https://www.bigcommerce.com/apps/category-merchandiser-by-space-48/',
  },
  {
    name: 'Metafields Manager',
    icon_url: '/img/icons/metafields-manager.png',
    description: 'Create & edit metafields on categories, products, brands and orders.',
    appstore_url: 'https://www.bigcommerce.com/apps/metafields-manager-by-space-48/',
  },
  {
    name: 'Page Scheduler',
    icon_url: '/img/icons/page-scheduler.png',
    description: 'Manage and schedule changes to your pages',
    appstore_url: 'https://www.bigcommerce.com/apps/page-scheduler-by-space-48/',
  },
  {
    name: 'Store Locator',
    icon_url: '/img/icons/store-locator.png',
    description: 'Help your customers find you & your products',
    appstore_url: 'https://www.bigcommerce.com/apps/store-locator-by-space-48/',
  },
  {
    name: 'Mega Menu Builder',
    icon_url: '/img/icons/mega-menu-builder.png',
    description: 'Full control and customisation of your store\'s navigation menus.',
    appstore_url: 'https://www.bigcommerce.com/apps/mega-menu-builder-by-space-48/',
  },
];

const Space48Apps = () => {
  return (
    <Panel header="Space48 Apps">
      <Text>We have filtered decades worth of ecommerce experience into apps rich with value.</Text>
      <AppsGrid>
        {
          appsConfig.map(app => {
            return (
              <AppsGridItem key={app.name}>
                  <Flex>
                    <FlexItem>
                      <img src={app.icon_url} alt={app.name} width="80" height="80"/>
                    </FlexItem>
                    <FlexItem marginHorizontal="large">
                      <H3>{app.name}</H3>
                      <Small>{app.description}</Small>
                      <Button
                        variant="secondary"
                      >
                        Learn more
                      </Button>
                    </FlexItem>
                  </Flex>
              </AppsGridItem>
            );
          })
        }
      </AppsGrid>
    </Panel>
  )
}

export default Space48Apps;
